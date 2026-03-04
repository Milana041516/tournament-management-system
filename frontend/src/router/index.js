import { createRouter, createWebHistory } from 'vue-router'

import Dashboard from '../views/Dashboard.vue'
import Tournaments from '../views/Tournaments.vue'
import Matches from '../views/Matches.vue'
import Teams from '../views/Teams.vue'
import Registrations from '../views/Registrations.vue'
import TournamentDetails from '../views/TournamentDetails.vue'

const routes = [
  { path: '/', component: Dashboard },
  { path: '/tournaments', component: Tournaments },
  { path: '/tournaments/:id', component: TournamentDetails },
  { path: '/matches', component: Matches },
  { path: '/teams', component: Teams },
  { path: '/registrations', component: Registrations }
]

const router = createRouter({
  history: createWebHistory(),
  routes
})

export default router
