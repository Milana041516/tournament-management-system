<template>
  <div>
    <h1 class="text-2xl sm:text-3xl font-bold text-gray-800 mb-4 sm:mb-6 flex items-center gap-2">
      <span class="material-icons-outlined text-3xl text-purple-600">app_registration</span>
      Player Registration
    </h1>

    <ErrorMessage 
      v-if="error"
      :title="error.title"
      :message="error.message"
      @retry="fetchData"
    />

    <div class="bg-white rounded-lg shadow p-4 sm:p-8 max-w-2xl mx-auto">
      <form @submit.prevent="submitRegistration">
       
        <div class="mb-4 sm:mb-6">
          <label class="block text-gray-700 font-semibold mb-2 text-sm sm:text-base flex items-center gap-2">
            <span class="material-icons-outlined text-lg">emoji_events</span>
            Select Tournament:
          </label>
          <select
            v-model="registrationForm.tournament_id"
            class="w-full border border-gray-300 rounded px-3 sm:px-4 py-2 text-sm sm:text-base"
            required
          >
            <option value="">Choose a tournament</option>
            <option v-for="tournament in tournaments" :key="tournament.id" :value="tournament.id">
              {{ tournament.name }}
            </option>
          </select>
        </div>

        
        <div class="mb-4 sm:mb-6">
          <label class="block text-gray-700 font-semibold mb-2 text-sm sm:text-base flex items-center gap-2">
            <span class="material-icons-outlined text-lg">groups</span>
            Choose Team (Optional):
          </label>
          <select
            v-model="registrationForm.team_id"
            class="w-full border border-gray-300 rounded px-3 sm:px-4 py-2 text-sm sm:text-base"
          >
            <option value="">Select a team</option>
            <option v-for="team in teams" :key="team.id" :value="team.id">
              {{ team.name }}
            </option>
          </select>
        </div>

        
        <div class="mb-4 sm:mb-6">
          <label class="block text-gray-700 font-semibold mb-2 text-sm sm:text-base flex items-center gap-2">
            <span class="material-icons-outlined text-lg">person</span>
            Your Name:
          </label>
          <input
            v-model="registrationForm.name"
            type="text"
            class="w-full border border-gray-300 rounded px-3 sm:px-4 py-2 text-sm sm:text-base"
            required
          />
        </div>

        
        <div class="mb-4 sm:mb-6">
          <label class="block text-gray-700 font-semibold mb-2 text-sm sm:text-base flex items-center gap-2">
            <span class="material-icons-outlined text-lg">email</span>
            Email:
          </label>
          <input
            v-model="registrationForm.email"
            type="email"
            class="w-full border border-gray-300 rounded px-3 sm:px-4 py-2 text-sm sm:text-base"
            required
          />
        </div>

        
        <button
          type="submit"
          class="w-full bg-blue-600 text-white px-6 py-3 rounded hover:bg-blue-700 transition font-semibold text-sm sm:text-base flex items-center justify-center gap-2"
        >
          <span class="material-icons-outlined text-lg">how_to_reg</span>
          Register
        </button>
      </form>
    </div>
  </div>
</template>

<script>
import axios from 'axios'
import API_URL from '../services/api'
import ErrorMessage from '../components/ErrorMessage.vue'
import { getErrorMessage } from '../utils/errorHandler'

export default {
  name: 'Registrations',

  components: {
    ErrorMessage
  },
  
  data() {
    return {
      tournaments: [],
      teams: [],
      error: null,
      registrationForm: {
        tournament_id: '',
        team_id: '',
        name: '',
        email: '',
      }
    }
  },
  
  methods: {
    async fetchData() {
      this.error = null
      await this.fetchTournaments()
      await this.fetchTeams()
    },
    
    async fetchTournaments() {
      try {
        const response = await axios.get(`${API_URL}/tournaments`)
        this.tournaments = response.data
      } catch (error) {
        console.error('Error fetching tournaments:', error)
        this.error = getErrorMessage(error)
        this.error.title = 'Error Loading Tournaments'
      }
    },
    
    async fetchTeams() {
      try {
        const response = await axios.get(`${API_URL}/teams`)
        this.teams = response.data
      } catch (error) {
        console.error('Error fetching teams:', error)
        if (!this.error) {
          this.error = getErrorMessage(error)
          this.error.title = 'Error Loading Teams'
        }
      }
    },
    
    async submitRegistration() {
      try {
        await axios.post(`${API_URL}/registrations`, this.registrationForm)
        window.showMessage('Registration submitted successfully!', 'success')
        
        
        this.registrationForm = {
          tournament_id: '',
          team_id: '',
          name: '',
          email: '',
        }
      } catch (error) {
        console.error('Error submitting registration:', error)
        const errorMsg = getErrorMessage(error)
        window.showMessage('Error submitting registration: ' + errorMsg.message, 'error')
      }
    }
  },
  
  mounted() {
    this.fetchData()
  }
}
</script>

