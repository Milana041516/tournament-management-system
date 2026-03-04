<template>
  <div>
    <ErrorMessage 
      v-if="error && !loading"
      :title="error.title"
      :message="error.message"
      @retry="fetchTournamentDetails"
    />

    <LoadingSpinner v-if="loading" />

    <div v-else-if="tournament && !error">
      <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-4 sm:mb-6 gap-3">
        <h1 class="text-2xl sm:text-3xl font-bold text-gray-800">Tournament Details</h1>
        <button
          @click="goBack"
          class="w-full sm:w-auto text-center text-gray-600 hover:text-gray-800 px-4 py-2 border border-gray-300 rounded hover:bg-gray-50 transition text-sm sm:text-base flex items-center justify-center gap-2"
        >
          <span class="material-icons-outlined text-lg">arrow_back</span>
          Back to Tournaments
        </button>
      </div>

      
      <div class="bg-white rounded-lg shadow p-4 sm:p-8 mb-6">
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start gap-4">
          <div class="flex-1">
            <div class="flex flex-col sm:flex-row sm:items-center gap-2 sm:gap-3 mb-3">
              <h2 class="text-2xl sm:text-3xl font-bold text-gray-800">{{ tournament.name }}</h2>
              <span 
                class="px-3 py-1 rounded-full text-xs sm:text-sm font-semibold capitalize w-fit"
                :class="statusColor"
              >
                {{ tournamentStatus }}
              </span>
            </div>
            <div class="space-y-2">
              <p class="text-gray-600 flex items-center gap-2 text-sm sm:text-base">
                <span class="material-icons-outlined text-lg">location_on</span>
                <span class="font-semibold">Location:</span>
                {{ tournament.location }}
              </p>
              <p class="text-gray-600 flex items-center gap-2 text-sm sm:text-base">
                <span class="material-icons-outlined text-lg">event</span>
                <span class="font-semibold">Duration:</span>
                {{ formatDate(tournament.start_date) }} - {{ formatDate(tournament.end_date) }}
              </p>
            </div>
          </div>
          <button
            v-if="tournamentStatus === 'upcoming'"
            @click="registerForTournament"
            class="w-full sm:w-auto bg-blue-600 text-white px-6 sm:px-8 py-3 rounded hover:bg-blue-700 transition font-semibold text-sm sm:text-base flex items-center justify-center gap-2"
          >
            <span class="material-icons-outlined text-lg">app_registration</span>
            Register Now
          </button>
        </div>
      </div>

      
      <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 sm:gap-6 mb-6">
        <div class="bg-white rounded-lg shadow p-4 sm:p-6">
          <div class="flex items-center justify-between mb-2">
            <p class="text-gray-600 text-xs sm:text-sm">Total Teams</p>
            <span class="material-icons-outlined text-2xl text-blue-600">groups</span>
          </div>
          <p class="text-2xl sm:text-3xl font-bold text-gray-800">{{ teams.length }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-4 sm:p-6">
          <div class="flex items-center justify-between mb-2">
            <p class="text-gray-600 text-xs sm:text-sm">Total Matches</p>
            <span class="material-icons-outlined text-2xl text-green-600">sports_soccer</span>
          </div>
          <p class="text-2xl sm:text-3xl font-bold text-gray-800">{{ matches.length }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-4 sm:p-6">
          <div class="flex items-center justify-between mb-2">
            <p class="text-gray-600 text-xs sm:text-sm">Registrations</p>
            <span class="material-icons-outlined text-2xl text-purple-600">how_to_reg</span>
          </div>
          <p class="text-2xl sm:text-3xl font-bold text-gray-800">{{ registrations.length }}</p>
        </div>
      </div>

      
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6 mb-6">
        <div class="bg-white rounded-lg shadow p-4 sm:p-6">
          <h3 class="text-lg sm:text-xl font-semibold text-gray-800 mb-4 flex items-center gap-2">
            <span class="material-icons-outlined text-2xl text-yellow-600">emoji_events</span>
            Participating Teams
          </h3>
          <div v-if="teams.length > 0" class="space-y-3">
            <div
              v-for="(team, index) in teams"
              :key="team.id"
              class="flex items-center justify-between p-3 bg-gray-50 rounded border border-gray-200"
            >
              <div class="flex items-center gap-2 sm:gap-3">
                <span class="font-semibold text-gray-700 text-base sm:text-lg">{{ index + 1 }}.</span>
                <span class="text-gray-800 font-medium text-sm sm:text-base">{{ team.name }}</span>
              </div>
            </div>
          </div>
          <p v-else class="text-gray-500 text-center py-8 text-sm sm:text-base">No teams registered yet</p>
        </div>

        <div class="bg-white rounded-lg shadow p-4 sm:p-6">
          <h3 class="text-lg sm:text-xl font-semibold text-gray-800 mb-4 flex items-center gap-2">
            <span class="material-icons-outlined text-2xl text-blue-600">schedule</span>
            Upcoming Matches
          </h3>
          <div v-if="upcomingMatches.length > 0" class="space-y-3">
            <div
              v-for="match in upcomingMatches"
              :key="match.id"
              class="p-3 sm:p-4 bg-blue-50 rounded border border-blue-200"
            >
              <p class="font-semibold text-gray-800 mb-1 text-sm sm:text-base">
                {{ match.team1 ? match.team1.name : 'TBD' }} <span class="text-blue-600">vs</span> {{ match.team2 ? match.team2.name : 'TBD' }}
              </p>
              <p class="text-xs sm:text-sm text-gray-600 flex items-center gap-1">
                <span class="material-icons-outlined text-base">event</span>
                {{ formatDateTime(match.match_date) }}
              </p>
            </div>
          </div>
          <p v-else class="text-gray-500 text-center py-8 text-sm sm:text-base">No upcoming matches</p>
        </div>
      </div>

      <div class="bg-white rounded-lg shadow p-4 sm:p-6">
        <h3 class="text-lg sm:text-xl font-semibold text-gray-800 mb-4 flex items-center gap-2">
          <span class="material-icons-outlined text-2xl text-green-600">scoreboard</span>
          Recent Results
        </h3>
        <div v-if="completedMatches.length > 0" class="space-y-3">
          <div
            v-for="match in completedMatches"
            :key="match.id"
            class="flex flex-col sm:flex-row sm:justify-between sm:items-center p-3 sm:p-4 border-b last:border-0 gap-2"
          >
            <div class="flex-1">
              <p class="font-semibold text-gray-800 mb-1 text-sm sm:text-base">
                {{ match.team1 ? match.team1.name : 'TBD' }} vs {{ match.team2 ? match.team2.name : 'TBD' }}
              </p>
              <p class="text-xs sm:text-sm text-gray-600 flex items-center gap-1">
                <span class="material-icons-outlined text-base">event</span>
                {{ formatDate(match.match_date) }}
              </p>
            </div>
            <div class="text-left sm:text-right">
              <p class="text-lg sm:text-xl font-bold text-blue-600">
                {{ match.team1_score }} - {{ match.team2_score }}
              </p>
            </div>
          </div>
        </div>
        <p v-else class="text-gray-500 text-center py-8 text-sm sm:text-base">No completed matches yet</p>
      </div>
    </div>

    <div v-else-if="!loading && !tournament && !error" class="text-center py-12 px-4">
      <span class="material-icons-outlined text-5xl text-gray-400 mb-4">error_outline</span>
      <p class="text-gray-500 mb-4 text-sm sm:text-base">Tournament not found</p>
      <button
        @click="goBack"
        class="text-blue-600 hover:text-blue-800 px-4 sm:px-6 py-2 border border-blue-600 rounded text-sm sm:text-base inline-flex items-center gap-2"
      >
        <span class="material-icons-outlined text-lg">arrow_back</span>
        Return to Tournaments
      </button>
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
  name: 'TournamentDetails',
  
  components: {
    LoadingSpinner,
    ErrorMessage
  },
  
  data() {
    return {
      tournament: null,
      teams: [],
      matches: [],
      registrations: [],
      loading: true,
      error: null
    }
  },
  
  computed: {
    tournamentStatus() {
      if (!this.tournament) return ''
      const now = new Date()
      const start = new Date(this.tournament.start_date)
      const end = new Date(this.tournament.end_date)
      
      if (now < start) return 'upcoming'
      if (now > end) return 'completed'
      return 'ongoing'
    },
    
    statusColor() {
      switch(this.tournamentStatus) {
        case 'upcoming': return 'bg-blue-100 text-blue-800'
        case 'ongoing': return 'bg-green-100 text-green-800'
        case 'completed': return 'bg-gray-100 text-gray-800'
        default: return 'bg-gray-100 text-gray-800'
      }
    },
    
    upcomingMatches() {
      return this.matches
        .filter(m => new Date(m.match_date) > new Date())
        .slice(0, 5)
    },
    
    completedMatches() {
      return this.matches
        .filter(m => m.team1_score !== null && m.team2_score !== null)
        .slice(0, 5)
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
    
    formatDateTime(date) {
      return new Date(date).toLocaleDateString('en-US', { 
        month: 'short', 
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
      })
    },
    
    async fetchTournamentDetails() {
      this.loading = true
      this.error = null
      
      try {
        const id = this.$route.params.id
        
        const [tournamentResponse, teamsResponse, matchesResponse, registrationsResponse] = await Promise.all([
          axios.get(`${API_URL}/tournaments/${id}`),
          axios.get(`${API_URL}/teams`),
          axios.get(`${API_URL}/matches`),
          axios.get(`${API_URL}/registrations`)
        ])
        
        this.tournament = tournamentResponse.data
        this.teams = teamsResponse.data.filter(t => t.tournament_id == id)
        this.matches = matchesResponse.data.filter(m => m.tournament_id == id)
        this.registrations = registrationsResponse.data.filter(r => r.tournament_id == id)
      } catch (error) {
        console.error('Error fetching tournament details:', error)
        this.error = getErrorMessage(error)
        this.error.title = 'Error Loading Tournament Details'
      } finally {
        this.loading = false
      }
    },
    
    goBack() {
      this.$router.push('/tournaments')
    },
    
    registerForTournament() {
      this.$router.push('/registrations')
    }
  },
  
  mounted() {
    this.fetchTournamentDetails()
  }
}
</script>

