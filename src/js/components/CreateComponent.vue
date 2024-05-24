<template>
  <div>
    <template v-if="breadcrumbs.length">
      <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li v-for="(breadcrumb, index) in breadcrumbs" :key="index"
            :class="['breadcrumb-item', { 'active': breadcrumb.form_master_id === form_master }]">
            <a v-if="breadcrumb.form_master_id !== form_master" href="#" @click.prevent="changeActiveBreadcrumb(breadcrumb.form_master_id)">{{ breadcrumb.breadcrumb_item }}</a>
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
            <div v-for="(value, key) in columns" :key="key" class="mb-3 col-sm-4">
              <label :for="value" class="form-label">{{
                formatString(value)
              }}</label>
              <template v-if="select.includes(value)">
                <multiselect v-model="selectedOption[value]" :options="selectOption[value]"
                  :placeholder="'Select ' + formatString(value)" label="text" track-by="text"
                  @select="handleSelect(value, $event)"></multiselect>
              </template>
              <template v-else>
                <template v-if="inputType[value] == 'textarea'">
                  <textarea :id="value" v-model="formData[value]" :type="inputType[value]"
                    class="form-control"></textarea>
                </template>
                <template v-else>
                  <input :id="value" v-model="formData[value]" :type="inputType[value]" class="form-control" />
                  <span v-show="error && error[value]" class="text-danger">{{
                    error[value]
                  }}</span>
                </template>
              </template>
            </div>
          </div>
          <div class="card-footer d-flex justify-content-center">
            <button type="submit" class="btn btn-primary">Submit</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script>
import Multiselect from "vue-multiselect";
import "vue-multiselect/dist/vue-multiselect.css";
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
      form_master: this.formid
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
        this.formData = {}; 
        this.form_master=response.data.form_master_id;
        if(this.breadcrumbs.length>0)
        {
          this.fetchColumns(`/form-api/create/${this.form_master}`);
        }
                 
      } catch (error) {
        console.log(error);
        if (error.response && error.response.data) {
          this.error = error.response.data.errors;
        }
      }
    },

    fetchColumns(url) {
      return axios
        .get(url)
        .then((response) => {
          this.header = response.data.header;
          this.columns = response.data.columns;
          this.select = Object.keys(response.data.select);
          this.selectOption = response.data.select;
          this.dependant = response.data.dependant;
          this.inputType = response.data.inputType;
          if(this.breadcrumbs.length===0)
          {
            this.breadcrumbs = response.data.breadcrumb;
          }

          const activeBreadcrumbItem = this.breadcrumbs.find(b => b.form_master_id == this.form_master);
          this.activeBreadcrumb = activeBreadcrumbItem ? activeBreadcrumbItem.breadcrumb_item : null;
        })
        .catch((error) => {
          console.error("Error fetching columns:", error);
          throw error; 
        });
    },
    handleSelect(key, selectedOption) {
      this.formData[key] = selectedOption.value;
      if (Object.keys(this.dependant).includes(key)) {
        this.fetchDependantData(this.dependant[key], selectedOption.value);
      }
    },
    formatString(value) {
      let parts = value.split("_");
      parts = parts.filter((part) => !part.toLowerCase().endsWith("id"));
      parts = parts.map((part) => part.charAt(0).toUpperCase() + part.slice(1));
      return parts.join(" ");
    },
    fetchDependantData(key, value) {
      const params = {
        [key]: value
      };
      axios
        .get(`/form-api/dependant/${this.form_master}`, { params })
        .then((response) => {
          Object.keys(response.data).forEach((key) => {
            this.selectedOption[key] = null;
            this.selectOption[key] = response.data[key];
          });
        })
        .catch((error) => { console.error("Error fetching dependant data:", error); });
    },
    changeActiveBreadcrumb(form_master)
    {
      this.form_master=form_master;
      this.fetchColumns(`/form-api/create/${this.form_master}`);
    }
  },
};
</script>
