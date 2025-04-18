
const routes = [
  {
    path: '/',
    component: () => import('layouts/MainLayout.vue'),
    children: [
      { path: '', component: () => import('pages/AboutPage.vue') },
      { name:'NewsPage',path: 'NewsPage/', component: () => import('pages/NewsPage.vue') }, //props: true
      { name:'StatisticsPage',path: 'StatisticsPage/', component: () => import('pages/StatisticsPage.vue') },
      { name:'TagsPage',path: 'TagsPage/', component: () => import('pages/TagsPage.vue') },
      { name:'WorldMapPage',path: 'WorldMapPage/', component: () => import('pages/WorldMapPage.vue') },
      { name:'SignUpPage',path: 'SignUpPage/', component: () => import('pages/SignUpPage.vue') },
      { name:'ReviewPage',path: 'ReviewPage/', component: () => import('pages/ReviewPage.vue') },
    ]
  },

  {
    path: '/:catchAll(.*)*',
    component: () => import('pages/ErrorNotFound.vue')
  }
]

export default routes
