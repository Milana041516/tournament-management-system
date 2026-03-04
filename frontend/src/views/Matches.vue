<template>
  <div>
    <h1 class="text-2xl sm:text-3xl font-bold text-gray-800 mb-4 sm:mb-6 flex items-center gap-2">
      <span class="material-icons-outlined text-3xl text-green-600">sports_soccer</span>
      Match Schedule - {{ tournamentName }}
    </h1>

    <ErrorMessage 
      v-if="error"
      :title="error.title"
      :message="error.message"
      @retry="fetchMatches"
    />

    <LoadingSpinner v-if="loading" />

    <div v-else-if="matches.length > 0">
      <div class="bg-white rounded-lg shadow mb-6">
        <div
          v-for="match in matches"
          :key="match.id"
          class="p-4 sm:p-6 border-b last:border-0 flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3"
        >
          <div class="flex-1">
            <p class="text-base sm:text-lg font-semibold text-gray-800 mb-1">
              {{ match.team1 ? match.team1.name : 'TBD '}} vs {{ match.team2 ? match.team2.name : 'TBD'}}
            </p>
            <p class="text-xs sm:text-sm text-gray-600 flex items-center gap-1">
              <span class="material-icons-outlined text-base">event</span>
              {{ formatDate(match.match_date) }} · Score: {{ match.score || 'TBD' }}
            </p>
          </div>
          <div class="flex flex-wrap gap-2">
            <button
              @click="editMatch(match)"
              class="flex-1 sm:flex-none bg-blue-600 text-white px-4 sm:px-6 py-2 rounded hover:bg-blue-700 transition text-xs sm:text-sm flex items-center justify-center gap-1"
            >
              <span class="material-icons-outlined text-sm">edit</span>
              Edit
            </button>
            <button
              @click="deleteMatch(match.id)"
              class="flex-1 sm:flex-none bg-red-600 text-white px-4 sm:px-6 py-2 rounded hover:bg-red-700 transition text-xs sm:text-sm flex items-center justify-center gap-1"
            >
              <span class="material-icons-outlined text-sm">delete</span>
              Delete
            </button>
          </div>
        </div>
      </div>

      <button
        @click="showAddForm = true"
        class="w-full sm:w-auto bg-blue-600 text-white px-6 py-3 rounded hover:bg-blue-700 transition text-sm sm:text-base flex items-center justify-center gap-2"
      >
        <span class="material-icons-outlined text-lg">add_circle</span>
        Add Match
      </button>
    </div>

    <div v-else-if="!loading && !error" class="text-center py-12">
      <span class="material-icons-outlined text-5xl text-gray-400 mb-4">sports_soccer</span>
      <p class="text-gray-500 text-lg mb-4">No matches scheduled yet.</p>
      <button
        @click="showAddForm = true"
        class="bg-blue-600 text-white px-6 py-3 rounded hover:bg-blue-700 transition"
      >
        Schedule Your First Match
      </button>
    </div>

    <div v-if="showAddForm" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
      <div class="bg-white rounded-lg p-4 sm:p-6 w-full max-w-2xl max-h-[90vh] overflow-y-auto">
        <h2 class="text-lg sm:text-xl font-semibold mb-4 flex items-center gap-2">
          <span class="material-icons-outlined text-2xl text-green-600">{{ editingMatch ? 'edit' : 'sports' }}</span>
          {{ editingMatch ? 'Edit Match' : 'Add Match' }}
        </h2>
        
        <form @submit.prevent="saveMatch">
          
          <div class="mb-4">
            <label class="block text-gray-700 font-semibold mb-2 text-sm sm:text-base">Tournament:</label>
            <select
              v-model="matchForm.tournament_id"
              class="w-full border border-gray-300 rounded px-3 sm:px-4 py-2 text-sm sm:text-base"
              required
            >
              <option value="">Select Tournament</option>
              <option v-for="tournament in tournaments" :key="tournament.id" :value="tournament.id">
                {{ tournament.name }}
              </option>
            </select>
          </div>

          
          <div class="mb-4">
            <label class="block text-gray-700 font-semibold mb-2 text-sm sm:text-base">Team 1:</label>
            <select
              v-model="matchForm.team1_id"
              class="w-full border border-gray-300 rounded px-3 sm:px-4 py-2 text-sm sm:text-base"
              required
            >
              <option value="">Select Team 1</option>
              <option v-for="team in teams" :key="team.id" :value="team.id">
                {{ team.name }}
              </option>
            </select>
          </div>

          
          <div class="mb-4">
            <label class="block text-gray-700 font-semibold mb-2 text-sm sm:text-base">Team 2:</label>
            <select
              v-model="matchForm.team2_id"
              class="w-full border border-gray-300 rounded px-3 sm:px-4 py-2 text-sm sm:text-base"
              required
            >
              <option value="">Select Team 2</option>
              <option v-for="team in teams" :key="team.id" :value="team.id">
                {{ team.name }}
              </option>
            </select>
          </div>

          
          <div class="mb-4">
            <label class="block text-gray-700 font-semibold mb-2 text-sm sm:text-base">Match Date & Time:</label>
            <input
              v-model="matchForm.match_date"
              type="datetime-local"
              class="w-full border border-gray-300 rounded px-3 sm:px-4 py-2 text-sm sm:text-base"
              required
            />
          </div>

          
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
            <div>
              <label class="block text-gray-700 font-semibold mb-2 text-sm sm:text-base">Team 1 Score:</label>
              <input
                v-model.number="matchForm.team1_score"
                type="number"
                min="0"
                class="w-full border border-gray-300 rounded px-3 sm:px-4 py-2 text-sm sm:text-base"
                placeholder="TBD"
              />
            </div>
            <div>
              <label class="block text-gray-700 font-semibold mb-2 text-sm sm:text-base">Team 2 Score:</label>
              <input
                v-model.number="matchForm.team2_score"
                type="number"
                min="0"
                class="w-full border border-gray-300 rounded px-3 sm:px-4 py-2 text-sm sm:text-base"
                placeholder="TBD"
              />
            </div>
          </div>

          
          <div class="flex flex-col sm:flex-row gap-2">
            <button
              type="submit"
              class="w-full sm:flex-1 bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700 text-sm sm:text-base flex items-center justify-center gap-2"
            >
              <span class="material-icons-outlined text-base">save</span>
              {{ editingMatch ? 'Update Match' : 'Create Match' }}
            </button>
            <button
              type="button"
              @click="cancelForm"
              class="w-full sm:flex-1 bg-gray-300 text-gray-700 px-6 py-2 rounded hover:bg-gray-400 text-sm sm:text-base flex items-center justify-center gap-2"
            >
              <span class="material-icons-outlined text-base">close</span>
              Cancel
            </button>
          </div>
        </form>
      </div>
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
  name: 'Matches',

  components: {
    LoadingSpinner,
    ErrorMessage
  },
  
  data() {
    return {
      matches: [],
      tournaments: [],
      teams: [],
      tournamentName: '',
      loading: false,
      error: null,
      showAddForm: false,
      editingMatch: null,
      matchForm: {
        tournament_id: '',
        team1_id: '',
        team2_id: '',
        match_date: '',
        team1_score: null,
        team2_score: null
      }
    }
  },
  
  methods: {
    formatDate(date) {
      return new Date(date).toLocaleDateString('en-US', { 
        month: 'long', 
        day: 'numeric', 
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
      })
    },
    
    async fetchMatches() {
      this.loading = true
      this.error = null
      
      try {
        const response = await axios.get(`${API_URL}/matches`)
        this.matches = response.data
        
        
        if (this.matches.length > 0 && this.matches[0].tournament_id) {
          const tournamentResponse = await axios.get(`${API_URL}/tournaments/${this.matches[0].tournament_id}`)
          this.tournamentName = tournamentResponse.data.name
        }
      } catch (error) {
        console.error('Error fetching matches:', error)
        this.error = getErrorMessage(error)
        this.error.title = 'Error Loading Matches'
      } finally {
        this.loading = false
      }
    },
    
    async fetchTournaments() {
      try {
        const response = await axios.get(`${API_URL}/tournaments`)
        this.tournaments = response.data
      } catch (error) {
        console.error('Error fetching tournaments:', error)
        const errorMsg = getErrorMessage(error)
        window.showMessage('Error loading tournaments: ' + errorMsg.message, 'error')
      }
    },
    
    async fetchTeams() {
      try {
        const response = await axios.get(`${API_URL}/teams`)
        this.teams = response.data
      } catch (error) {
        console.error('Error fetching teams:', error)
        const errorMsg = getErrorMessage(error)
        window.showMessage('Error loading teams: ' + errorMsg.message, 'error')
      }
    },
    
    async saveMatch() {
      try {
        if (this.editingMatch) {
          await axios.put(`${API_URL}/matches/${this.editingMatch.id}`, this.matchForm)
          window.showMessage('Match updated successfully!', 'success')
        } else {
          await axios.post(`${API_URL}/matches`, this.matchForm)
          window.showMessage('Match created successfully!', 'success')
        }
        
        await this.fetchMatches()
        this.cancelForm()
      } catch (error) {
        console.error('Error saving match:', error)
        const errorMsg = getErrorMessage(error)
        window.showMessage('Error saving match: ' + errorMsg.message, 'error')
      }
    },
    
    editMatch(match) {
      this.editingMatch = match
      
      const matchDate = new Date(match.match_date)
      const formattedDate = matchDate.toISOString().slice(0, 16)
      
      this.matchForm = {
        tournament_id: match.tournament_id,
        team1_id: match.team1_id,
        team2_id: match.team2_id,
        match_date: formattedDate,
        team1_score: match.team1_score,
        team2_score: match.team2_score
      }
      this.showAddForm = true
    },
    
    async deleteMatch(id) {
      if (confirm('Are you sure you want to delete this match?')) {
        try {
          await axios.delete(`${API_URL}/matches/${id}`)
          window.showMessage('Match deleted successfully!', 'success')
          await this.fetchMatches()
        } catch (error) {
          console.error('Error deleting match:', error)
          const errorMsg = getErrorMessage(error)
          window.showMessage('Error deleting match: ' + errorMsg.message, 'error')
        }
      }
    },
    
    cancelForm() {
      this.showAddForm = false
      this.editingMatch = null
      this.matchForm = {
        tournament_id: '',
        team1_id: '',
        team2_id: '',
        match_date: '',
        team1_score: null,
        team2_score: null
      }
    }
  },
  
  mounted() {
    this.fetchMatches()
    this.fetchTournaments()
    this.fetchTeams()
  }
}
</script>

