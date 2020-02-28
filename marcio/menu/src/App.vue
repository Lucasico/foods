<template>
  <div class="app">
  <v-app id="inspire">
    <v-navigation-drawer
      v-model="drawer"
      app
    >
      <v-list dense>
        <v-app id="inspire">
      <v-card max-width="500" class="mx-auto">
         
          <v-list>
            <v-list-group
                v-for="item in items"
                :key="item.title"
                v-model="item.active"
                :prepend-icon="item.action"
                no-action>

              <template v-slot:activator>
                  <v-list-item-content>
                      <v-list-item-title v-text="item.title"></v-list-item-title>
                  </v-list-item-content>
              </template>
      
              <v-list-item
                  v-for="subItem in item.items"
                  :key="subItem.title"
                  
                   :to="subItem.to">
                <v-list-item-content>
                    <v-list-item-title v-text="subItem.title"></v-list-item-title>
                </v-list-item-content>
              </v-list-item>
            </v-list-group>
          </v-list>
    </v-card>
  </v-app>
      </v-list>
    </v-navigation-drawer>

    <v-app-bar
        app
        color="indigo"
        dark>

      <v-app-bar-nav-icon @click.stop="drawer = !drawer"></v-app-bar-nav-icon>
      <v-toolbar-title>Application</v-toolbar-title>
    </v-app-bar>

    <v-content>
        <v-container
          fluid
          fill-height>
          <v-layout
            align-center
            justify-center>
            <v-flex text-xs-center>
            <router-view /> <!--Mostrando rotas-->
            </v-flex>
          </v-layout>
        </v-container>
    </v-content>
    <v-footer
        color="indigo"
        app>
        <span class="white--text">&copy; 2019</span>
    </v-footer>
  </v-app>
</div>

</template>

<script>
export default {
  data(){
    return {
      drawer: null,
      items: [
        {
          action: 'mdi-city-variant',
          title: 'EMPRESAS',
          items: [
            { title: 'Listar', to: '/listaEmpresas'},
            { title: 'Cadastrar', to: '' },
            { title: 'Apagar' },
            { title: 'Atualizar' }
          ],
        }, 
        {
          action: 'mdi-account',
          title: 'CLIENTES',
          items: [
            { title: 'Listar', to: '/listaClientes'},
            { title: 'Cadastrar', to: '/cadastroClientes'},
            { title: 'Apagar' },
            { title: 'Atualizar' }
          ],
        },
        {
          action: 'mdi-cube-outline',
          title: 'PRODUTOS',
          items: [
            { title: 'Listar' },
            { title: 'Cadastrar' },
            { title: 'Apagar' },
            { title: 'Atualizar' }
          ],
        }
      ]
    }
  },
  methods: {
    acionar(){
      this.s = 0
    }
  }
}
</script>

<style>

</style>