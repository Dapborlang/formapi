<template>
    <div>
        <template v-if="breadcrumbs.length">
            <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li v-for="(breadcrumb, index) in breadcrumbs" :key="index"
                        :class="['breadcrumb-item', { 'active': breadcrumb.form_master_id === form_master }]">
                        <a v-if="breadcrumb.form_master_id !== form_master" href="#"
                            @click.prevent="changeActiveBreadcrumb(breadcrumb.form_master_id)">{{
                            breadcrumb.breadcrumb_item }}</a>
                        <span v-else>{{ breadcrumb.breadcrumb_item }}</span>
                    </li>
                </ol>
            </nav>
        </template>

        <div class="card shadow">
            <div class="card-header bg-primary text-white">{{ header }}</div>
            <div class="card-body">
                <form @submit.prevent="submitForm">
                    <div class="row">
                        <template v-for="(value, key) in columns" :key="key">
                            <template v-if="onColumnName==value">
                                <input :id="value" v-model="formData[value]" type="hidden" />
                            </template>
                            <template v-else>
                                <div class="mb-3 col-sm-4 col-lg-3">
                                    <label :for="value" class="form-label">{{ formatString(value) }}</label>
                                    <template v-if="select.includes(value)">
                                        <multiselect v-model="selectedOption[value]" :options="selectOption[value]"
                                            :placeholder="'Select ' + formatString(value)" label="text" track-by="text"
                                            @select="handleSelect(value, $event)"></multiselect>
                                    </template>
                                    <template v-else>
                                        <template v-if="inputType[value] === 'textarea'">
                                            <textarea :id="value" v-model="formData[value]"
                                                class="form-control"></textarea>
                                        </template>
                                        <template v-else>
                                            <input :id="value" v-model="formData[value]" :type="inputType[value]"
                                                class="form-control" />
                                            <span v-show="error && error[value]" class="text-danger">{{ error[value]
                                                }}</span>
                                        </template>
                                    </template>
                                </div>
                            </template>
                        </template>
                    </div>
                    <div class="card-footer d-flex justify-content-center">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
        <hr>
        <index-component :formid="form_master" ref="indexComponent"></index-component>
    </div>
</template>

<script>
import Multiselect from "vue-multiselect";
import "vue-multiselect/dist/vue-multiselect.css";
import axios from 'axios';

export default {
    props: ["formid"],
    components: { Multiselect },
    data() {
        return {
            header: "",
            columns: {},
            inputType: [],
            select: [],
            selectOption: [],
            dependant: [],
            formData: {},
            error: {},
            selectedOption: {},
            breadcrumbs: [],
            activeBreadcrumb: null,
            form_master: this.formid,
            onColumnName: ""
        };
    },
    mounted() {
        this.fetchColumns(`/form-api/create/${this.form_master}`);
    },
    methods: {
        async submitForm() {
            try {
                const response = await axios.post(`/form-api/${this.formid}/${this.form_master}`, this.formData);
                console.log("Form submitted successfully:", response.data);

                if (response.data.breadcrumbData) {
                    if (response.data.breadcrumbData.form_master_id != null)
                    {
                        this.form_master = response.data.breadcrumbData.form_master_id;
                    }
                    if (response.data.breadcrumbData.on_column_name != null) {
                        // this.onColumnName.push(response.data.breadcrumbData.on_column_name);
                        this.onColumnName=response.data.breadcrumbData.on_column_name;
                        this.formData[response.data.breadcrumbData.on_column_name] = response.data.from_column_name;
                    }
                }
                
                this.updateIndexComponent();
                if (this.breadcrumbs.length > 0) {
                    this.fetchColumns(`/form-api/create/${this.form_master}`);
                }
            } catch (error) {
                console.log(error);
                if (error.response && error.response.data) {
                    this.error = error.response.data.errors;
                }
            }
        },
        async fetchColumns(url) {
            try {
                const response = await axios.get(url);
                this.header = response.data.header;
                this.columns = response.data.columns;
                this.select = Object.keys(response.data.select);
                this.selectOption = response.data.select;
                this.dependant = response.data.dependant;
                this.inputType = response.data.inputType;

                if (this.breadcrumbs.length === 0) {
                    this.breadcrumbs = response.data.breadcrumb;
                }

                const activeBreadcrumbItem = this.breadcrumbs.find(b => b.form_master_id == this.form_master);
                this.activeBreadcrumb = activeBreadcrumbItem ? activeBreadcrumbItem.breadcrumb_item : null;
            } catch (error) {
                console.error("Error fetching columns:", error);
            }
        },
        handleSelect(key, selectedOption) {
            this.formData[key] = selectedOption.value;
            if (this.dependant[key]) {
                this.fetchDependantData(this.dependant[key], selectedOption.value);
            }
        },
        formatString(value) {
            return value.split("_")
                .filter(part => !part.toLowerCase().endsWith("id"))
                .map(part => part.charAt(0).toUpperCase() + part.slice(1))
                .join(" ");
        },
        async fetchDependantData(key, value) {
            try {
                const params = { [key]: value };
                const response = await axios.get(`/form-api/dependant/${this.form_master}`, { params });
                Object.keys(response.data).forEach(depKey => {
                    this.selectedOption[depKey] = null;
                    this.selectOption[depKey] = response.data[depKey];
                });
            } catch (error) {
                console.error("Error fetching dependant data:", error);
            }
        },
        changeActiveBreadcrumb(form_master) {
            this.form_master = form_master;
            this.fetchColumns(`/form-api/create/${this.form_master}`);
        },
        updateIndexComponent() {
            this.$refs.indexComponent.updateData();
        },
    }
};
</script>