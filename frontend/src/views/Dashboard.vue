<template>
  <div>
    <h1 class="text-2xl sm:text-3xl font-bold text-gray-800 mb-4 sm:mb-6 flex items-center gap-2">
      <span class="material-icons-outlined text-3xl text-blue-600">dashboard</span>
      Admin Dashboard
    </h1>

    <ErrorMessage 
      v-if="error"
      :title="error.title"
      :message="error.message"
      @retry="fetchPendingRegistrations"
    />

    <LoadingSpinner v-if="loading" />
    

    <div class="bg-white rounded-lg shadow p-4 sm:p-6 mb-6 sm:mb-8">
      <h2 class="text-xl sm:text-2xl font-semibold text-gray-800 mb-4 flex items-center gap-2">
        <span class="material-icons-outlined text-2xl text-blue-600">emoji_events</span>
        Manage Tournaments
      </h2>
      <div class="flex flex-col sm:flex-row gap-3 sm:space-x-4 sm:gap-0">
        <button
          @click="showCreateForm = true"
          class="w-full sm:w-auto bg-blue-600 text-white px-6 py-3 rounded hover:bg-blue-700 transition text-sm sm:text-base flex items-center justify-center gap-2"
        >
          <span class="material-icons-outlined text-lg">add_circle</span>
          Create Tournament
        </button>
        <router-link
          to="/tournaments"
          class="w-full sm:w-auto text-center inline-block bg-blue-600 text-white px-6 py-3 rounded hover:bg-blue-700 transition text-sm sm:text-base flex items-center justify-center gap-2"
        >
          <span class="material-icons-outlined text-lg">list</span>
          View Tournaments
        </router-link>
      </div>
    </div>

    <div class="bg-white rounded-lg shadow p-4 sm:p-6">
      <h2 class="text-xl sm:text-2xl font-semibold text-gray-800 mb-4 flex items-center gap-2">
        <span class="material-icons-outlined text-2xl text-purple-600">how_to_reg</span>
        Player Approvals
      </h2>

      <LoadingSpinner v-if="loading" />

      <div v-else-if="pendingRegistrations.length > 0" class="space-y-4">
        <div
          v-for="registration in pendingRegistrations"
          :key="registration.id"
          class="flex flex-col sm:flex-row sm:justify-between sm:items-center border-b pb-4 last:border-0 gap-3"
        >
          <div class="flex-1">
            <p class="font-semibold text-gray-800 text-sm sm:text-base">{{ registration.user?.name }} - {{ registration.team?.name || 'Solo Entry' }}</p>
            <p class="text-xs sm:text-sm text-gray-600">{{ registration.tournament?.name }}</p>
          </div>
          <div class="flex flex-wrap gap-2">
            <span class="text-yellow-600 bg-yellow-100 px-3 py-1 rounded text-xs sm:text-sm flex items-center gap-1">
              <span class="material-icons-outlined text-sm">pending</span>
              Pending
            </span>
            <button
              @click="approveRegistration(registration.id)"
              class="text-green-600 hover:text-green-800 px-3 sm:px-4 py-1 border border-green-600 rounded text-xs sm:text-sm flex items-center gap-1"
            >
              <span class="material-icons-outlined text-sm">check_circle</span>
              Approve
            </button>
            <button
              @click="rejectRegistration(registration.id)"
              class="text-red-600 hover:text-red-800 px-3 sm:px-4 py-1 border border-red-600 rounded text-xs sm:text-sm flex items-center gap-1"
            >
              <span class="material-icons-outlined text-sm">cancel</span>
              Reject
            </button>
          </div>
        </div>
      </div>

      <div v-else-if="!loading && !error" class="text-center py-8">
        <span class="material-icons-outlined text-4xl text-gray-400 mb-3">check_circle</span>
        <p class="text-gray-500">No pending registrations at the moment.</p>
      </div>
    </div>

    <div v-if="showCreateForm" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
      <div class="bg-white rounded-lg p-4 sm:p-8 w-full max-w-2xl max-h-[90vh] overflow-y-auto">
        <h2 class="text-xl sm:text-2xl font-semibold text-gray-800 mb-4 sm:mb-6">Create New Tournament</h2>
        
        <form @submit.prevent="createTournament">
          
          <div class="mb-4">
            <label class="block text-gray-700 font-semibold mb-2 text-sm sm:text-base">Tournament Name:</label>
            <input
              v-model="tournamentForm.name"
              type="text"
              class="w-full border border-gray-300 rounded px-3 sm:px-4 py-2 text-sm sm:text-base"
              placeholder="Enter tournament name"
              required
            />
          </div>

        
          <div class="mb-4">
            <label class="block text-gray-700 font-semibold mb-2 text-sm sm:text-base">Location:</label>
            <input
              v-model="tournamentForm.location"
              type="text"
              class="w-full border border-gray-300 rounded px-3 sm:px-4 py-2 text-sm sm:text-base"
              placeholder="Enter location"
              required
            />
          </div>

         
          <div class="mb-4">
            <label class="block text-gray-700 font-semibold mb-2 text-sm sm:text-base">Description:</label>
            <textarea
              v-model="tournamentForm.description"
              class="w-full border border-gray-300 rounded px-3 sm:px-4 py-2 text-sm sm:text-base"
              placeholder="Enter tournament description"
              rows="3"
            ></textarea>
          </div>

          
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
            <div>
              <label class="block text-gray-700 font-semibold mb-2 text-sm sm:text-base">Start Date:</label>
              <input
                v-model="tournamentForm.start_date"
                type="date"
                class="w-full border border-gray-300 rounded px-3 sm:px-4 py-2 text-sm sm:text-base"
                required
              />
            </div>
            <div>
              <label class="block text-gray-700 font-semibold mb-2 text-sm sm:text-base">End Date:</label>
              <input
                v-model="tournamentForm.end_date"
                type="date"
                class="w-full border border-gray-300 rounded px-3 sm:px-4 py-2 text-sm sm:text-base"
                required
              />
            </div>
          </div>

        
          <div class="mb-6">
            <label class="block text-gray-700 font-semibold mb-2 text-sm sm:text-base">Status:</label>
            <select
              v-model="tournamentForm.status"
              class="w-full border border-gray-300 rounded px-3 sm:px-4 py-2 text-sm sm:text-base"
            >
              <option value="upcoming">Upcoming</option>
              <option value="ongoing">Ongoing</option>
              <option value="completed">Completed</option>
            </select>
          </div>

         
          <div class="flex flex-col sm:flex-row gap-2">
            <button
              type="submit"
              class="w-full sm:flex-1 bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700 font-semibold text-sm sm:text-base"
            >
              Create Tournament
            </button>
            <button
              type="button"
              @click="cancelCreateForm"
              class="w-full sm:flex-1 bg-gray-300 text-gray-700 px-6 py-2 rounded hover:bg-gray-400 text-sm sm:text-base"
            >
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
  name: 'Dashboard',

  components: {
    LoadingSpinner,
    ErrorMessage
  },
  
  data() {
    return {
      pendingRegistrations: [],
      showCreateForm: false,
      loading: false,
      error: null,
      tournamentForm: {
        name: '',
        location: '',
        description: '',
        start_date: '',
        end_date: '',
        status: 'upcoming'
      }
    }
  },
  
  methods: {
    async fetchPendingRegistrations() {
      this.loading = true
      this.error = null
      
      try {
        const response = await axios.get(`${API_URL}/registrations`)
        this.pendingRegistrations = response.data.filter(r => r.status === 'pending')
      } catch (error) {
        console.error('Error fetching registrations:', error)
        this.error = getErrorMessage(error)
        this.error.title = 'Error Loading Registrations'
      } finally {
        this.loading = false
      }
    },
    
    async createTournament() {
      try {
        await axios.post(`${API_URL}/tournaments`, this.tournamentForm)
        window.showMessage('Tournament created successfully!', 'success')
      
        this.cancelCreateForm()
        
       
        this.$router.push('/tournaments')
      } catch (error) {
        console.error('Error creating tournament:', error)
        const errorMsg = getErrorMessage(error)
        window.showMessage('Error creating tournament: ' + errorMsg.message, 'error')
      }
    },
    
    cancelCreateForm() {
      this.showCreateForm = false
      this.tournamentForm = {
        name: '',
        location: '',
        description: '',
        start_date: '',
        end_date: '',
        status: 'upcoming'
      }
    },
    
    async approveRegistration(id) {
      try {
        await axios.put(`${API_URL}/registrations/${id}`, { status: 'approved' })
        window.showMessage('Registration approved!', 'success')
        await this.fetchPendingRegistrations()
      } catch (error) {
        console.error('Error approving registration:', error)
        const errorMsg = getErrorMessage(error)
        window.showMessage('Error approving registration: ' + errorMsg.message, 'error')
      }
    },
    
    async rejectRegistration(id) {
      try {
        await axios.put(`${API_URL}/registrations/${id}`, { status: 'rejected' })
        window.showMessage('Registration rejected!', 'success')
        await this.fetchPendingRegistrations()
      } catch (error) {
        console.error('Error rejecting registration:', error)
        const errorMsg = getErrorMessage(error)
        window.showMessage('Error rejecting registration: ' + errorMsg.message, 'error')
      }
    }
  },
  
  mounted() {
    this.fetchPendingRegistrations()
  }
}
</script>

