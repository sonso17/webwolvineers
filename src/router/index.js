import { createRouter, createWebHashHistory } from 'vue-router'
import PaginaInici from '../views/PaginaInici.vue'
import logIn from '../views/LogIn.vue'

const routes = [
  {
    path: '/',
    name: 'home',
    component: PaginaInici
  },
  {
    path: '/about',
    name: 'about',
    // route level code-splitting
    // this generates a separate chunk (about.[hash].js) for this route
    // which is lazy-loaded when the route is visited.
    component: () => import(/* webpackChunkName: "about" */ '../views/AboutView.vue')
  },
  {
    path: '/logIn',
    name: 'logIn',
    component: logIn
  }
]

const router = createRouter({
  history: createWebHashHistory(),
  routes
})

export default router
