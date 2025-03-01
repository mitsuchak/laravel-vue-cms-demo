<template>
  <div class="form-container">
    <h1 class="form-title">{{ formTitle }}</h1>
    <form @submit.prevent="submitForm" class="form">
      <!-- Title Field -->
      <div class="form-group">
        <label for="title" class="form-label">Title:</label>
        <input type="text" v-model="form.title" id="title" class="form-input" required>
      </div>

      <!-- Slug Field -->
      <div class="form-group">
        <label for="slug" class="form-label">Slug:</label>
        <input type="text" v-model="form.slug" id="slug" class="form-input" required>
      </div>

      <!-- Content Field -->
      <div class="form-group">
        <label for="content" class="form-label">Content:</label>
        <textarea v-model="form.content" id="content" class="form-textarea" required></textarea>
      </div>

      <!-- Parent Page Dropdown -->
      <div class="form-group">
        <label for="parent_id" class="form-label">Parent Page:</label>
        <select v-model="form.parent_id" id="parent_id" class="form-select">
          <option value="">None</option>
          <option v-for="page in flattenedPages" :key="page.id" :value="page.id">
            {{ page.title }}
          </option>
        </select>
      </div>

      <!-- Buttons -->
      <div class="form-buttons">
        <button type="submit" class="form-button form-button--submit">{{ formButtonText }}</button>
        <button type="button" @click="cancelForm" class="form-button form-button--cancel">Cancel</button>
      </div>
    </form>

    <!-- Display Validation Errors -->
    <div v-if="errors.length > 0" class="form-errors">
      <h3 class="form-errors-title">Errors:</h3>
      <ul class="form-errors-list">
        <li v-for="error in errors" :key="error" class="form-errors-item">{{ error }}</li>
      </ul>
    </div>
  </div>
</template>

<script>
export default {
  name: 'PageForm',
  props: {
    pageId: {
      type: Number,
      default: null,
    },
    pages: {
      type: Array,
      required: true,
    },
  },
  data() {
    return {
      form: {
        title: '',
        slug: '',
        content: '',
        parent_id: null,
      },
      errors: [], // Store validation errors
    };
  },
  computed: {
    // Dynamic form title based on create/edit mode
    formTitle() {
      return this.pageId ? 'Edit Page' : 'Create Page';
    },
    // Dynamic button text based on create/edit mode
    formButtonText() {
      return this.pageId ? 'Update' : 'Create';
    },
    // Flatten the pages array for the dropdown
    flattenedPages() {
      return this.flattenPages(this.pages);
    },
  },
  async created() {
    // If editing, fetch the page data
    if (this.pageId) {
      const response = await fetch(`/api/pages/${this.pageId}`);
      const page = await response.json();
      this.form = { ...page };
    }
  },
  methods: {
    // Recursively flatten the pages array
    flattenPages(pages, level = 0) {
      let flattened = [];
      pages.forEach(page => {
        // Add the current page with indentation based on its level
        flattened.push({ ...page, title: '— '.repeat(level) + page.title });
        // Recursively add child pages
        if (page.children && page.children.length) {
          flattened = flattened.concat(this.flattenPages(page.children, level + 1));
        }
      });
      return flattened;
    },

    // Handle form submission
    async submitForm() {
      // Clear previous errors
      this.errors = [];

      // Determine the API endpoint and HTTP method
      const url = this.pageId ? `/api/pages/${this.pageId}` : '/api/pages';
      const method = this.pageId ? 'PUT' : 'POST';

      try {
        // Send the form data to the API
        const response = await fetch(url, {
          method,
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
          },
          body: JSON.stringify(this.form),
        });

        // Handle the API response
        if (response.ok) {
          // Emit an event to notify the parent component
          this.$emit('form-submitted');
        } else {
          // Handle validation errors
          const errorData = await response.json();
          if (errorData.errors) {
            // Extract and display validation errors
            this.errors = Object.values(errorData.errors).flat();
          } else {
            // Display a generic error message
            this.errors = ['Failed to submit the form. Please try again.'];
          }
        }
      } catch (error) {
        // Handle network or other errors
        console.error('Error:', error);
        this.errors = ['An error occurred. Please check the console for details.'];
      }
    },

    // Cancel the form
    cancelForm() {
      this.$emit('cancel-form'); // Emit the cancel event
    },
  },
};
</script>

<style scoped>
.form-container {
  max-width: 600px;
  margin: 2rem auto;
  padding: 2rem;
  background-color: #ffffff;
  border-radius: 12px;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.form-title {
  font-size: 2rem;
  font-weight: 600;
  color: #333;
  text-align: center;
  margin-bottom: 1.5rem;
}

.form {
  display: flex;
  flex-direction: column;
  gap: 1.5rem;
}

.form-group {
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
}

.form-label {
  font-size: 1rem;
  font-weight: 500;
  color: #555;
}

.form-input,
.form-textarea,
.form-select {
  padding: 0.75rem;
  border: 1px solid #ddd;
  border-radius: 8px;
  font-size: 1rem;
  color: #333;
  background-color: #f9f9f9;
  transition: border-color 0.3s ease, box-shadow 0.3s ease;
}

.form-input:focus,
.form-textarea:focus,
.form-select:focus {
  border-color: #4CAF50;
  box-shadow: 0 0 0 3px rgba(76, 175, 80, 0.2);
  outline: none;
}

.form-textarea {
  height: 150px;
  resize: vertical;
}

.form-buttons {
  display: flex;
  justify-content: flex-end;
  gap: 1rem;
  margin-top: 1rem;
}

.form-button {
  padding: 0.75rem 1.5rem;
  border: none;
  border-radius: 8px;
  font-size: 1rem;
  font-weight: 500;
  cursor: pointer;
  transition: background-color 0.3s ease, transform 0.2s ease;
}

.form-button--submit {
  background-color: #4CAF50;
  color: white;
}

.form-button--cancel {
  background-color: #f44336;
  color: white;
}

.form-button:hover {
  opacity: 0.9;
  transform: translateY(-2px);
}

.form-button:active {
  transform: translateY(0);
}

.form-errors {
  margin-top: 2rem;
  padding: 1rem;
  border: 1px solid #ff0000;
  border-radius: 8px;
  background-color: #ffe6e6;
  color: #ff0000;
}

.form-errors-title {
  font-size: 1.25rem;
  font-weight: 600;
  margin-bottom: 0.5rem;
}

.form-errors-list {
  padding-left: 1.5rem;
}

.form-errors-item {
  font-size: 0.9rem;
  margin-bottom: 0.25rem;
}
</style>