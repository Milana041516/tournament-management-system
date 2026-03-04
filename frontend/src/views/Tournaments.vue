<template>
  <div>
    <h1 class="text-2xl sm:text-3xl font-bold text-gray-800 mb-4 sm:mb-6 flex items-center gap-2">
      <span class="material-icons-outlined text-3xl text-blue-600">emoji_events</span>
      Tournaments
    </h1>

    <ErrorMessage 
      v-if="error"
      :title="error.title"
      :message="error.message"
      @retry="fetchTournaments"
    />

    <LoadingSpinner v-if="loading" />

    <div v-else-if="tournaments.length > 0" class="space-y-4">
      <div
        v-for="tournament in tournaments"
        :key="tournament.id"
        class="bg-white rounded-lg shadow p-4 sm:p-6 flex flex-col sm:flex-row sm:justify-between gap-4"
      >
        <div class="flex-1">
          <h2 class="text-lg sm:text-xl font-semibold text-gray-800 mb-2">{{ tournament.name }}</h2>
          <p class="text-gray-600 text-xs sm:text-sm mb-1 flex items-center gap-1">
            <span class="material-icons-outlined text-base">location_on</span>
            {{ tournament.location }}
          </p>
          <p class="text-gray-600 text-xs sm:text-sm flex items-center gap-1">
            <span class="material-icons-outlined text-base">event</span>
            {{ formatDate(tournament.start_date) }} - {{ formatDate(tournament.end_date) }}
          </p>
        </div>
        <div class="flex flex-col sm:flex-row gap-2 sm:items-start">
          <router-link
            :to="`/tournaments/${tournament.id}`"
            class="w-full sm:w-auto text-center bg-blue-600 text-white px-4 sm:px-6 py-2 rounded hover:bg-blue-700 transition text-sm sm:text-base flex items-center justify-center gap-2"
          >
            <span class="material-icons-outlined text-base">visibility</span>
            View Details
          </router-link>
          <button
            @click="deleteTournament(tournament.id)"
            class="w-full sm:w-auto bg-red-600 text-white px-4 sm:px-6 py-2 rounded hover:bg-red-700 transition text-sm sm:text-base flex items-center justify-center gap-2"
          >
            <span class="material-icons-outlined text-base">delete</span>
            Delete
          </button>
        </div>
      </div>
    </div>

    <div v-else-if="!loading && !error" class="text-center py-12">
      <span class="material-icons-outlined text-5xl text-gray-400 mb-4">emoji_events</span>
      <p class="text-gray-500 text-lg">No tournaments available yet.</p>
      <router-link 
        to="/"
        class="inline-block mt-4 text-blue-600 hover:text-blue-800"
      >
        Create your first tournament
      </router-link>
    </div>
  </div>
</template>

<script>
import axios from 'axios'
import API_URL from '../services/api'
import LoadingSpinner from '../components/LoadingSpinner.vue'
import ErrorMessage from '../components/ErrorMessage.vue'
import { getErrorMessage } from '../utils/errorHandler'

export default {
  name: 'Tournaments',

  components: {
    LoadingSpinner,
    ErrorMessage
  },
  
  data() {
    return {
      tournaments: [],
      loading: false,
      error: null
    }
  },
  
  methods: {
    formatDate(date) {
      return new Date(date).toLocaleDateString('en-US', { 
        month: 'long', 
        day: 'numeric', 
        year: 'numeric' 
      })
    },
    
    async fetchTournaments() {
      this.loading = true
      this.error = null
      
      try {
        const response = await axios.get(`${API_URL}/tournaments`)
        this.tournaments = response.data
      } catch (error) {
        console.error('Error fetching tournaments:', error)
        this.error = getErrorMessage(error)
        this.error.title = 'Error Loading Tournaments'
      } finally {
        this.loading = false
      }
    },
    
    async deleteTournament(id) {
      if (confirm('Are you sure you want to delete this tournament? This will also delete all related teams, matches, and registrations.')) {
        try {
          await axios.delete(`${API_URL}/tournaments/${id}`)
          window.showMessage('Tournament deleted successfully!', 'success')
          await this.fetchTournaments() 
        } catch (error) {
          console.error('Error deleting tournament:', error)
          const errorMsg = getErrorMessage(error)
          window.showMessage('Error deleting tournament: ' + errorMsg.message, 'error')
        }
      }
    }
  },
  
  mounted() {
    this.fetchTournaments()
  }
}
</script>

<style>
</style>

