import { createRouter, createWebHashHistory } from 'vue-router'
import PaginaInici from '../views/PaginaInici.vue'
import logIn from '../views/0LogIn.vue'
import userInfo from '../views/0UserInfo.vue'
import registerR from '../views/0registerR.vue'
import userModify from '../views/0userModify.vue'

import AllCategories from '@/views/1AllCategories.vue'
import CreateCategory from '@/views/1CreateCategory.vue'
import ModifyCategory from '@/views/1ModifyCategory.vue'

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
  },
  {
    path: '/register',
    name: 'register',
    component: registerR
  },
  {
    path: '/userInfo/:id',
    name: 'userInfo',
    component: userInfo
  },
  {
    path: '/modifyUser/:id',
    name: 'modifyUser',
    component: userModify
  },
  {
    path: '/allCategories',
    name: 'allCategories',
    component: AllCategories
  },
  {
    path: '/CreateCategory',
    name: 'CreateCategory',
    component: CreateCategory
  },
  {
    path: '/ModifyCategory/:id',
    name: 'ModifyCategory',
    component: ModifyCategory
  }
]

const router = createRouter({
  history: createWebHashHistory(),
  routes
})

export default router
