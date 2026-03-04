<template>
  <transition name="slide-fade">
    <div 
      v-if="visible" 
      :class="alertClasses"
      class="fixed top-4 left-1/2 transform -translate-x-1/2 z-[9999] px-6 py-4 rounded-lg shadow-lg max-w-md w-full flex items-center justify-between"
    >
      <div class="flex items-center">
        <span class="material-icons-outlined text-2xl mr-3">{{ icon }}</span>
        <span class="font-medium">{{ message }}</span>
      </div>
      <button 
        @click="closeAlert" 
        class="text-white hover:text-gray-200 ml-4"
      >
        <span class="material-icons-outlined text-lg">close</span>
      </button>
    </div>
  </transition>
</template>

<script>
export default {
  name: 'MessageAlert',
  
  data() {
    return {
      visible: false,
      message: '',
      type: 'success',
      timeout: null
    }
  },
  
  computed: {
    alertClasses() {
      const types = {
        success: 'bg-green-500 text-white',
        error: 'bg-red-500 text-white',
        warning: 'bg-yellow-500 text-white',
        info: 'bg-blue-500 text-white'
      }
      return types[this.type]
    },
    
    icon() {
      const icons = {
        success: 'check_circle',
        error: 'error',
        warning: 'warning',
        info: 'info'
      }
      return icons[this.type]
    }
  },
  
  methods: {
    show(message, type = 'success', duration = 3000) {
      this.message = message
      this.type = type
      this.visible = true
      
      if (this.timeout) {
        clearTimeout(this.timeout)
      }
      
      this.timeout = setTimeout(() => {
        this.closeAlert()
      }, duration)
    },
    
    closeAlert() {
      this.visible = false
    }
  }
}
</script>

<style scoped>
.slide-fade-enter-active {
  transition: all 0.3s ease;
}

.slide-fade-leave-active {
  transition: all 0.3s ease;
}

.slide-fade-enter-from {
  transform: translate(-50%, -20px);
  opacity: 0;
}

.slide-fade-leave-to {
  transform: translate(-50%, -20px);
  opacity: 0;
}
</style>