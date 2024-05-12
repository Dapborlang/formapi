<template>
  <div>
    <div class="card">
      <div class="card-header">{{ header }}</div>
      <div class="card-body">
        <form @submit.prevent="submitForm">
          <div class="row">
            <div
              v-for="(value, key) in columns"
              :key="key"
              class="mb-3 col-sm-4"
            >
              <label :for="value" class="form-label">{{
                formatString(value)
              }}</label>
              <template v-if="select.includes(value)">
                <multiselect
                  v-model="selectedOption[value]"
                  :options="selectOption[value]"
                  :placeholder="'Select ' + formatString(value)"
                  label="text"
                  track-by="text"
                  @select="handleSelect(value, $event)"
                ></multiselect>
              </template>
              <template v-else>
                <template v-if="inputType[value] == 'textarea'">
                  <textarea
                    :id="value"
                    v-model="formData[value]"
                    :type="inputType[value]"
                    class="form-control"
                  ></textarea>
                </template>
                <template v-else>
                  <input
                    :id="value"
                    v-model="formData[value]"
                    :type="inputType[value]"
                    class="form-control"
                  />
                  <span v-show="error && error[value]" class="text-danger">{{
                    error[value]
                  }}</span>
                </template>
              </template>
            </div>
          </div>
          <div class="card-footer d-flex justify-content-center">
            <button type="submit" class="btn btn-primary">Save Changes</button>
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
  props: ["formid", "id"],
  watch: {
    id(newVal, oldVal) {
      if (newVal !== oldVal) {
        this.getPreviousData();
      }
    },
  },
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
    };
  },
  mounted() {
    this.fetchColumns();
  },
  methods: {
    fetchColumns() {
      axios
        .get(`/form-api/create/${this.formid}`)
        .then((response) => {
          this.header = response.data.header;
          this.columns = response.data.columns;
          this.select = Object.keys(response.data.select);
          this.selectOption = response.data.select;
          this.dependant = response.data.dependant;
          this.inputType = response.data.inputType;
          this.getPreviousData();
        })
        .catch((error) => {
          console.error("Error fetching columns:", error);
        });
    },
    submitForm() {
      axios
        .put(`/form-api/${this.formid}/${this.id}`, this.formData)
        .then((response) => {
          console.log("Form submitted successfully:", response.data);
          this.$emit("actionTaken");
        })
        .catch((error) => {
          this.error = error.response.data.errors;
        });
    },
    handleSelect(key, selectedOption) {
      this.formData[key] = selectedOption.value;
      console.log(selectedOption);
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
        [key]: value,
      };
      axios
        .get(`/form-api/dependant/${this.formid}`, { params })
        .then((response) => {
          Object.keys(response.data).forEach((key) => {
            this.selectedOption[key] = null;
            this.selectOption[key] = response.data[key];
          });
        })
        .catch((error) => {});
    },

    getPreviousData() {
      axios
        .get(`/form-api/edit/${this.formid}/${this.id}`)
        .then((response) => {
          Object.entries(this.columns).forEach(([key, value]) => {
            if (this.select.includes(value)) {
              const option = this.selectOption[value].find(
                (option) => option.value === response.data[value]
              );
              if (option) {
                this.selectedOption[value] = option;
              }
            } else {
              this.formData[value] = response.data[value];
            }
          });
        })
        .catch((error) => {});
    },
  },
};
</script>
