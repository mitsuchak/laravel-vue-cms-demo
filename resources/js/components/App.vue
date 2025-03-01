<template>
  <div class="app-container">
    <!-- Page Title with Animation -->
    <h1 class="app-title" :class="{ 'animate-title': !showForm }">CMS Page Tree</h1>

    <!-- Create New Page Button (Hidden when form is visible) -->
    <button
      v-if="!showForm"
      class="create-button"
      @click="showCreateForm"
    >
      <span class="button-text">Create New Page</span>
    </button>

    <!-- PageList Component (Hidden when form is visible) -->
    <transition name="fade">
      <PageList
        v-if="!showForm"
        :pages="pages"
        @delete-page="handleDeletePage"
        @edit-page="handleEditPage"
      />
    </transition>

    <!-- PageForm Component (Visible when form is active) -->
    <transition name="slide">
      <PageForm
        v-if="showForm"
        :pageId="editPageId"
        :pages="pages"
        @form-submitted="handleFormSubmission"
        @cancel-form="hideForm"
      />
    </transition>

    <!-- Router View for Dynamic Page Content -->
    <router-view></router-view>
  </div>
</template>

<script>
import PageList from './PageList.vue';
import PageForm from './PageForm.vue';

export default {
  components: {
    PageList,
    PageForm,
  },
  data() {
    return {
      pages: [], // List of pages fetched from the backend
      showForm: false, // Whether to show the form
      editPageId: null, // ID of the page being edited
    };
  },
  mounted() {
    this.fetchPages();
  },
  methods: {
    // Fetch pages from the backend
    async fetchPages() {
      const response = await fetch('/api/pages');
      this.pages = await response.json();
    },

    // Show the create form
    showCreateForm() {
      this.showForm = true;
      this.editPageId = null;
    },

    // Hide the form
    hideForm() {
      this.showForm = false;
      this.editPageId = null;
    },

    // Handle form submission
    handleFormSubmission() {
      this.hideForm(); // Hide the form
      this.fetchPages(); // Refresh the page list
    },

    // Handle page deletion
    handleDeletePage(pageId) {
      this.pages = this.removePageById(this.pages, pageId);
    },

    // Recursively remove a page by its ID
    removePageById(pages, pageId) {
      return pages
        .filter(page => page.id !== pageId) // Remove the page with the matching ID
        .map(page => {
          if (page.children && page.children.length) {
            // Recursively remove the page from children
            page.children = this.removePageById(page.children, pageId);
          }
          return page;
        });
    },

    // Handle edit button click
    handleEditPage(pageId) {
      this.editPageId = pageId;
      this.showForm = true;
    },
  },
};
</script>

<style>
/* Global styles */
body {
  font-family: 'Roboto', sans-serif;
  background-color: #f5f5f5;
  margin: 0;
  padding: 20px;
}

.app-container {
  max-width: 800px;
  margin: 0 auto;
  padding: 20px;
  background-color: white;
  border-radius: 10px;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

/* Page Title with Animation */
.app-title {
  color: #333;
  text-align: center;
  margin-bottom: 20px;
  font-size: 2rem;
  position: relative;
}

.animate-title {
  animation: pulse 2s infinite;
}

@keyframes pulse {
  0% {
    transform: scale(1);
  }
  50% {
    transform: scale(1.05);
  }
  100% {
    transform: scale(1);
  }
}

/* Create New Page Button */
.create-button {
  background-color: #4CAF50; /* Green background */
  color: white; /* White text */
  padding: 12px 24px; /* Padding */
  border: none; /* Remove border */
  border-radius: 25px; /* Rounded corners */
  cursor: pointer; /* Pointer cursor on hover */
  font-size: 16px; /* Font size */
  transition: all 0.3s ease; /* Smooth hover effect */
  display: block;
  margin: 0 auto 20px;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.create-button:hover {
  background-color: #45a049; /* Darker green on hover */
  transform: translateY(-2px); /* Slight lift on hover */
  box-shadow: 0 6px 8px rgba(0, 0, 0, 0.15);
}

.create-button:active {
  background-color: #3d8b40; /* Even darker green on click */
  transform: translateY(0); /* Reset lift on click */
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

/* Fade Animation */
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.5s ease;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}

/* Slide Animation */
.slide-enter-active,
.slide-leave-active {
  transition: transform 0.5s ease, opacity 0.5s ease;
}

.slide-enter-from,
.slide-leave-to {
  transform: translateX(100%);
  opacity: 0;
}
</style>