<template>
  <div>
    <h1>{{ page.title }}</h1>
    <div v-html="page.content"></div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  data() {
    return {
      page: {
        title: '',
        content: '',
      },
    };
  },
  watch: {
    '$route.params.slug': {
      immediate: true,
      handler(newSlug) {
        this.fetchPage(newSlug);
      },
    },
  },
  methods: {
    async fetchPage(slug) {
      try {
        const response = await axios.get(`/api/slug-pages/${slug}`);
        this.page = response.data;
      } catch (error) {
        console.error('Error fetching page:', error);
        this.page = { title: 'Page Not Found', content: 'The requested page does not exist.' };
      }
    },
  },
};
</script>

<style scoped>
h1 {
  margin-bottom: 20px;
}
</style>