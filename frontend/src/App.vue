<template>
  <div class="min-h-screen bg-gray-50">
    <Navbar />
    <MessageAlert ref="globalMessage" />
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 sm:py-8">
      <router-view />
    </div>
  </div>
</template>

<script>
import Navbar from './components/Navbar.vue'
import MessageAlert from './components/MessageAlert.vue'

export default {
  components: {
    Navbar,
    MessageAlert
  },

  methods: {
    showMessage(message, type) {
      this.$refs.globalMessage.show(message, type)
    }
  },

  mounted() {
    window.showMessage = (message, type) => {
      this.showMessage(message, type)
    }
  }
}
</script>