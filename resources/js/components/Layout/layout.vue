<template>
<div :class="{root: true, chatOpen, sidebarClose, sidebarStatic}">
  <Sidebar />
  <Helper />
  <div class="wrap">
    <Header id="header"/>
    <Chat />
    <v-touch class="content" @swipeleft="handleSwipe" @swiperight="handleSwipe" :swipe-options="{direction: 'horizontal', threshold: 100}">
      <router-view id="router-view"></router-view>
    </v-touch>
  </div>
  <footer class="contentFooter">
      <small>2019 &copy; EatDeli INC. <br> Sing Vue Version - Made by Flatlogic</small>
  </footer>
</div>
</template>

<script>
import { mapState, mapActions } from 'vuex';

import Sidebar from '../Sidebar/sidebar';
import Header from '../Header/header';
import Chat from '../Chat/chat';
import Helper from '../Helper/helper';

// import './layout.scss';

export default {
  name: 'Layout',
  components: { Sidebar, Header, Chat, Helper },
  methods: {
    ...mapActions(
      'layout', ['switchSidebar', 'handleSwipe', 'changeSidebarActive'],
    ),
  },
  computed: {
    ...mapState('layout', {
      sidebarClose: state => state.sidebarClose,
      sidebarStatic: state => state.sidebarStatic,
      chatOpen: state => state.chatOpen,
    }),
    loader() {
      return true;
    }
  },
  created() {
    const staticSidebar = JSON.parse(localStorage.getItem('sidebarStatic'));

    if (staticSidebar) {
      this.$store.state.layout.sidebarStatic = true;
    } else if (!this.sidebarClose) {
      setTimeout(() => {
        this.switchSidebar(true);
        this.changeSidebarActive(null);
      }, 2500);
    }
  },
};
</script>

<style src="./layout.scss" lang="scss" scoped></style>
<style>
    #header{
      width:100% !important;
      height:16vh !important;
    }
    .content{
      width:98% !important;
      height:72vh !important;
      margin:0 !important;
    }
    .contentFoooter {
      width:100% !important;
      height:5vh !important;
    }
</style>
