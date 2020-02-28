import Vue from 'vue'
import Router from 'vue-router'

import Inicio from './inicio'
import listaClientes from './components/template/clientes/listClients'
import cadastroCliente from './components/template/clientes/register'

Vue.use(Router) 

export default new Router({
  mode: 'history',
  routes: [
    {path: '/', component: Inicio},
    {path: '/listaClientes', component: listaClientes},
    {path: '/cadastroClientes', component: cadastroCliente}
  ]
})