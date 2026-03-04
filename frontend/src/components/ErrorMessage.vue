<template>
  <div v-if="visible" class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
    <div class="flex items-start gap-3">
      <span class="material-icons-outlined text-2xl text-red-600">error_outline</span>
      <div class="flex-1">
        <h3 class="text-red-800 font-semibold mb-1">{{ title }}</h3>
        <p class="text-red-600 text-sm">{{ message }}</p>
        <button 
          v-if="showRetry"
          @click="$emit('retry')"
          class="mt-3 bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700 transition text-sm flex items-center gap-2"
        >
          <span class="material-icons-outlined text-sm">refresh</span>
          Try Again
        </button>
      </div>
      <button 
        @click="close"
        class="text-red-600 hover:text-red-800"
      >
        <span class="material-icons-outlined">close</span>
      </button>
    </div>
  </div>
</template>

<script>
export default {
  name: 'ErrorMessage',
  
  props: {
    title: {
      type: String,
      default: 'Error'
    },
    message: {
      type: String,
      default: 'Something went wrong. Please try again.'
    },
    showRetry: {
      type: Boolean,
      default: true
    }
  },
  
  data() {
    return {
      visible: true
    }
  },
  
  methods: {
    close() {
      this.visible = false
    }
  }
}
</script>
