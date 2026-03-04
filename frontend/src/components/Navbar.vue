<template>
  <nav class="bg-white shadow-md mb-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex justify-between items-center h-16">
        
        <div class="flex items-center space-x-2">
          <span class="material-icons-outlined text-3xl text-blue-600">emoji_events</span>
          <span class="text-lg sm:text-xl font-bold text-gray-800">TournamentManagement</span>
        </div>

        
        <button 
          @click="mobileMenuOpen = !mobileMenuOpen"
          class="md:hidden text-gray-600 hover:text-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 rounded p-2"
          aria-label="Toggle menu"
        >
          <span class="material-icons-outlined text-2xl">{{ mobileMenuOpen ? 'close' : 'menu' }}</span>
        </button>

        
        <div class="hidden md:flex space-x-1 lg:space-x-4">
          <router-link 
            to="/" 
            class="text-gray-600 hover:text-blue-600 hover:bg-blue-50 px-3 py-2 rounded transition text-sm lg:text-base"
          >
            Dashboard
          </router-link>
          <router-link 
            to="/tournaments" 
            class="text-gray-600 hover:text-blue-600 hover:bg-blue-50 px-3 py-2 rounded transition text-sm lg:text-base"
          >
            Tournaments
          </router-link>
          <router-link 
            to="/teams" 
            class="text-gray-600 hover:text-blue-600 hover:bg-blue-50 px-3 py-2 rounded transition text-sm lg:text-base"
          >
            Teams
          </router-link>
          <router-link 
            to="/matches" 
            class="text-gray-600 hover:text-blue-600 hover:bg-blue-50 px-3 py-2 rounded transition text-sm lg:text-base"
          >
            Matches
          </router-link>
          <router-link 
            to="/registrations" 
            class="text-gray-600 hover:text-blue-600 hover:bg-blue-50 px-3 py-2 rounded transition text-sm lg:text-base"
          >
            Registrations
          </router-link>
        </div>
      </div>

      
      <div 
        v-if="mobileMenuOpen" 
        class="md:hidden pb-4 border-t border-gray-200 mt-2 pt-2"
      >
        <router-link 
          to="/" 
          @click="mobileMenuOpen = false"
          class="block text-gray-600 hover:text-blue-600 hover:bg-blue-50 px-3 py-2 rounded transition"
        >
          Dashboard
        </router-link>
        <router-link 
          to="/tournaments" 
          @click="mobileMenuOpen = false"
          class="block text-gray-600 hover:text-blue-600 hover:bg-blue-50 px-3 py-2 rounded transition"
        >
          Tournaments
        </router-link>
        <router-link 
          to="/teams" 
          @click="mobileMenuOpen = false"
          class="block text-gray-600 hover:text-blue-600 hover:bg-blue-50 px-3 py-2 rounded transition"
        >
          Teams
        </router-link>
        <router-link 
          to="/matches" 
          @click="mobileMenuOpen = false"
          class="block text-gray-600 hover:text-blue-600 hover:bg-blue-50 px-3 py-2 rounded transition"
        >
          Matches
        </router-link>
        <router-link 
          to="/registrations" 
          @click="mobileMenuOpen = false"
          class="block text-gray-600 hover:text-blue-600 hover:bg-blue-50 px-3 py-2 rounded transition"
        >
          Registrations
        </router-link>
      </div>
    </div>
  </nav>
</template>

<script>
export default {
  name: 'Navbar',
  
  data() {
    return {
      mobileMenuOpen: false
    }
  }
}
</script>