<template>
  <div class="card shadow" >
    <div class="card-header bg-primary text-white">{{ header }}</div>
    <div class="card-body" style="min-height: 300px; max-height: 600px; overflow-y: auto;">
      <table class="table table-bordered">
        <thead>
          <tr>
            <th v-for="(value, key) in columns" :key="key">{{ formatString(value) }}</th>
            <th>Option</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(entry, index) in data" :key="index">
            <td v-for="col in columns" :key="col">{{ entry[col] }}</td>
            <td>
              <button type="button" class="btn btn-secondary" @click="editData(entry['id'])">Edit</button>|
              <button type="button" class="btn btn-danger" @click="deleteData(entry['id'])">Delete</button>
            </td>
          </tr>
        </tbody>
      </table>
      <nav>
        <ul class="pagination">
          <li v-if="pagination.prev_page_url" class="page-item">
            <a class="page-link" href="#" @click="fetchColumns(pagination.prev_page_url)">Previous</a>
          </li>
          <li v-if="pagination.next_page_url" class="page-item">
            <a class="page-link" href="#" @click="fetchColumns(pagination.next_page_url)">Next</a>
          </li>
        </ul>
      </nav>
      Showing data from {{ pagination.from }} to {{ pagination.to }} of {{ pagination.total }}
    </div>
    <div class="modal fade" id="editDeleteModal" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
      <div class="modal-dialog modal-xl">
        <div class="modal-content">
          <div class="modal-header bg-info bg-gradient">
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <edit-component :formid="formid" :id="id" @actionTaken="handleAction"></edit-component>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { Modal } from "bootstrap";
export default {
  props: ["formid"],
  watch: {
    formid(newVal, oldVal) {
      if (newVal !== oldVal) {
        this.fetchColumns(`/form-api/${this.formid}`);
      }
    },
  },
  data() {
    return {
      id: 1,
      header: "",
      columns: {},
      data: [],
      pagination: {},
      modal: null,
    };
  },
  mounted() {
    this.fetchColumns(`/form-api/${this.formid}`);
    this.modal = new Modal(document.getElementById("editDeleteModal"));
  },
  methods: {
    fetchColumns(url) {
      axios
        .get(url)
        .then((response) => {
          this.header = response.data.header;
          this.columns = response.data.column;
          this.data = response.data.data;
          this.pagination = response.data.pagination;
        })
        .catch((error) => {
          console.error("Error fetching columns:", error);
        });
    },

    formatString(value) {
      let parts = value.split("_");
      parts = parts.filter((part) => !part.toLowerCase().endsWith("id"));
      parts = parts.map((part) => part.charAt(0).toUpperCase() + part.slice(1));
      return parts.join(" ");
    },

    editData(value) {
      this.id = value;
      this.modal.show();
    },

    deleteData(value) {
      const userResponse = confirm("Are you sure you want to delete this item?");
      if (userResponse) {
        axios
          .delete(`/form-api/${this.formid}/${value}`)
          .then((response) => {
            console.log(response.data);
            this.fetchColumns(`/form-api/${this.formid}`);
          })
          .catch((error) => {
            console.error("Error deleting the item:", error);
          });
      }
    },
    handleAction() {
      this.fetchColumns(`/form-api/${this.formid}`);
      this.modal.hide();
    },
    updateData() {
      this.fetchColumns(`/form-api/${this.formid}`); // Refetch data to update the component
    },
  },
};
</script>
