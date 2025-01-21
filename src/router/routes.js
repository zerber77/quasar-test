
const routes = [
  {
    path: '/',
    component: () => import('layouts/MainLayout.vue'),
    children: [
      { path: '', component: () => import('pages/IndexPage.vue') },
      { name:'NewsPage',path: '/NewsPage', component: () => import('pages/NewsPage.vue') }, //props: true
      { name:'AboutPage',path: '/AboutPage', component: () => import('pages/AboutPage.vue') },
    ]
  },

  {
    path: '/:catchAll(.*)*',
    component: () => import('pages/ErrorNotFound.vue')
  }
]

export default routes
