<template>
  <ul class="page-list">
    <!-- Loop through pages and render them -->
    <li v-for="page in pages" :key="page.id" class="page-item">
      <div class="page-content">
        {{ page.title }}
        <div class="page-actions">
          <button @click="editPage(page.id)" class="edit-button">Edit</button>
          <button @click="deletePage(page.id)" class="delete-button">Delete</button>
        </div>
      </div>
      <!-- Recursively render child pages -->
      <PageList
        v-if="page.children && page.children.length"
        :pages="page.children"
        @delete-page="handleDeletePage"
        @edit-page="handleEditPage"
      />
    </li>
  </ul>
</template>

<script>
export default {
  name: 'PageList',
  props: {
    pages: {
      type: Array,
      required: true,
    },
  },
  methods: {
    // Handle page edit
    editPage(pageId) {
      this.$emit('edit-page', pageId);
    },

    // Handle page deletion
    async deletePage(pageId) {
      if (confirm('Are you sure you want to delete this page?')) {
        try {
          const response = await fetch(`/api/pages/${pageId}`, {
            method: 'DELETE',
            headers: {
              'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            },
          });

          if (response.ok) {
            this.$emit('delete-page', pageId);
          } else {
            alert('Failed to delete the page. Please try again.');
          }
        } catch (error) {
          console.error('Error:', error);
          alert('An error occurred. Please check the console for details.');
        }
      }
    },

    // Handle delete event from child components
    handleDeletePage(pageId) {
      this.$emit('delete-page', pageId);
    },

    // Handle edit event from child components
    handleEditPage(pageId) {
      this.$emit('edit-page', pageId);
    },
  },
};
</script>

<style scoped>
.page-list {
  list-style-type: none;
  padding-left: 20px;
}

.page-item {
  margin: 10px 0;
  padding: 15px;
  background-color: white;
  border-radius: 8px;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
  transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.page-item:hover {
  transform: translateY(-2px);
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
}

.page-content {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.page-actions button {
  margin-left: 10px;
  padding: 5px 10px;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  font-size: 14px;
  transition: background-color 0.3s ease;
}

.edit-button {
  background-color: #4CAF50; /* Green */
  color: white;
}

.edit-button:hover {
  background-color: #45a049; /* Darker green */
}

.delete-button {
  background-color: #f44336; /* Red */
  color: white;
}

.delete-button:hover {
  background-color: #d32f2f; /* Darker red */
}
</style>