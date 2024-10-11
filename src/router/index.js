import { createRouter, createWebHashHistory } from 'vue-router'
import PaginaInici from '../views/PaginaInici.vue'
import logIn from '../views/LogIn.vue'
import userInfo from '../views/UserInfo.vue'
import registerR from '../views/registerR.vue'
import userModify from '../views/userModify.vue'
import createArticle from '@/views/createArticle.vue'
import getArticle from '@/views/getArticle.vue'
import modifyArticle from '@/views/modifyArticle.vue'
import AllArticles from '@/views/AllArticles.vue'

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
    path: '/createArticle',
    name: 'createArticle',
    component: createArticle
  },
  {
    path: '/getArticle/:id',
    props: true,
    name: 'getArticle',
    component: getArticle
  },
  
  {
    path: '/AllArticles',
    name: 'AllArticles',
    component: AllArticles
  },
  {
    path: '/modifyArticle/:id',
    name: 'modifyArticle',
    component: modifyArticle
  }
]

const router = createRouter({
  history: createWebHashHistory(),
  routes
})

export default router
