<template>
  <div>
    <h1 class="text-2xl sm:text-3xl font-bold text-gray-800 mb-4 sm:mb-6 flex items-center gap-2">
      <span class="material-icons-outlined text-3xl text-blue-600">groups</span>
      Teams Management
    </h1>

    <ErrorMessage 
      v-if="error"
      :title="error.title"
      :message="error.message"
      @retry="fetchTeams"
    />

    <LoadingSpinner v-if="loading" />

    <div v-else-if="teams.length > 0">
      <div class="bg-white rounded-lg shadow p-4 sm:p-6 mb-6">
        <div class="space-y-4">
          <div
            v-for="team in teams"
            :key="team.id"
            class="flex flex-col sm:flex-row sm:justify-between sm:items-center border-b pb-4 last:border-0 gap-3"
          >
            <span class="text-base sm:text-lg text-gray-800">{{ team.name }}</span>
            <div class="flex flex-wrap gap-2">
              <button
                @click="editTeam(team)"
                class="text-blue-600 hover:text-blue-800 px-3 sm:px-4 py-1 border border-blue-600 rounded text-xs sm:text-sm flex items-center gap-1"
              >
                <span class="material-icons-outlined text-sm">edit</span>
                Edit
              </button>
              <button
                @click="deleteTeam(team.id)"
                class="text-red-600 hover:text-red-800 px-3 sm:px-4 py-1 border border-red-600 rounded text-xs sm:text-sm flex items-center gap-1"
              >
                <span class="material-icons-outlined text-sm">delete</span>
                Delete
              </button>
            </div>
          </div>
        </div>
      </div>

     
      <button
        @click="showAddForm = true"
        class="w-full sm:w-auto bg-blue-600 text-white px-6 py-3 rounded hover:bg-blue-700 transition text-sm sm:text-base flex items-center justify-center gap-2"
      >
        <span class="material-icons-outlined text-lg">add_circle</span>
        Add Team
      </button>
    </div>

    <div v-else-if="!loading && !error" class="text-center py-12">
      <span class="material-icons-outlined text-5xl text-gray-400 mb-4">groups</span>
      <p class="text-gray-500 text-lg mb-4">No teams created yet.</p>
      <button
        @click="showAddForm = true"
        class="bg-blue-600 text-white px-6 py-3 rounded hover:bg-blue-700 transition"
      >
        Create Your First Team
      </button>
    </div>

    <div v-if="showAddForm" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
      <div class="bg-white rounded-lg p-4 sm:p-6 w-full max-w-md">
        <h2 class="text-lg sm:text-xl font-semibold mb-4 flex items-center gap-2">
          <span class="material-icons-outlined text-2xl text-blue-600">{{ editingTeam ? 'edit' : 'group_add' }}</span>
          {{ editingTeam ? 'Edit Team' : 'Add Team' }}
        </h2>
        
        <input
          v-model="teamForm.name"
          type="text"
          placeholder="Team Name"
          class="w-full border border-gray-300 rounded px-3 sm:px-4 py-2 mb-4 text-sm sm:text-base"
        />
        
        <select
          v-model="teamForm.tournament_id"
          class="w-full border border-gray-300 rounded px-3 sm:px-4 py-2 mb-4 text-sm sm:text-base"
        >
          <option value="">Select Tournament</option>
          <option v-for="tournament in tournaments" :key="tournament.id" :value="tournament.id">
            {{ tournament.name }}
          </option>
        </select>
        
        <div class="flex flex-col sm:flex-row gap-2">
          <button
            @click="saveTeam"
            class="w-full sm:flex-1 bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700 text-sm sm:text-base flex items-center justify-center gap-2"
          >
            <span class="material-icons-outlined text-lg">save</span>
            Save
          </button>
          <button
            @click="cancelForm"
            class="w-full sm:flex-1 bg-gray-300 text-gray-700 px-6 py-2 rounded hover:bg-gray-400 text-sm sm:text-base flex items-center justify-center gap-2"
          >
            <span class="material-icons-outlined text-lg">close</span>
            Cancel
          </button>
        </div>
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
  name: 'Teams',
  
  components: {
    LoadingSpinner,
    ErrorMessage
  },
  
  data() {
    return {
      teams: [],
      tournaments: [],
      showAddForm: false,
      editingTeam: null,
      loading: false,
      error: null,
      teamForm: {
        name: '',
        tournament_id: ''
      }
    }
  },
  
  methods: {
    async fetchTeams() {
      this.loading = true
      this.error = null
      
      try {
        const response = await axios.get(`${API_URL}/teams`)
        this.teams = response.data
      } catch (error) {
        console.error('Error fetching teams:', error)
        this.error = getErrorMessage(error)
        this.error.title = 'Error Loading Teams'
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
    
    async saveTeam() {
      try {
        if (this.editingTeam) {
          await axios.put(`${API_URL}/teams/${this.editingTeam.id}`, this.teamForm)
          window.showMessage('Team updated successfully!', 'success')
        } else {
          await axios.post(`${API_URL}/teams`, this.teamForm)
          window.showMessage('Team created successfully!', 'success')
        }
        
        await this.fetchTeams()
        this.cancelForm()
      } catch (error) {
        console.error('Error saving team:', error)
        const errorMsg = getErrorMessage(error)
        window.showMessage('Error saving team: ' + errorMsg.message, 'error')
      }
    },
    
    editTeam(team) {
      this.editingTeam = team
      this.teamForm = { 
        name: team.name,
        tournament_id: team.tournament_id
      }
      this.showAddForm = true
    },
    
    async deleteTeam(id) {
      if (confirm('Are you sure you want to delete this team?')) {
        try {
          await axios.delete(`${API_URL}/teams/${id}`)
          window.showMessage('Team deleted successfully!', 'success')
          await this.fetchTeams()
        } catch (error) {
          console.error('Error deleting team:', error)
          const errorMsg = getErrorMessage(error)
          window.showMessage('Error deleting team: ' + errorMsg.message, 'error')
        }
      }
    },
    
    cancelForm() {
      this.showAddForm = false
      this.editingTeam = null
      this.teamForm = {
        name: '',
        tournament_id: ''
      }
    }
  },
  
  mounted() {
    this.fetchTeams()
    this.fetchTournaments()
  }
}
</script>