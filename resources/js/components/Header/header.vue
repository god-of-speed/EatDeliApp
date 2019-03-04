<template>
    <div class="header d-print-none">
        <nav class="navbar navbar-expand-lg">
            <a class="navbar-brand" href="#">
                <i class="fa fa-circle text-gray mr-n-sm" />
                <i class="fa fa-circle text-warning" />
                    &nbsp;
                    EatDeli
                    &nbsp;
                <i class="fa fa-circle text-warning mr-n-sm" />
                <i class="fa fa-circle text-gray" />
            </a>
          </nav>
        <nav class="navbar navbar-expand-lg">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item active">
                    <a class="nav-link" @click="toggleSidebarMethod">
                        <i class='la la-bars la-lg' />
                        <span class="sr-only">(current)</span>
                    </a>
                </li>
                <li class="nav-item" v-if="!isAuthenticated" >
                    <router-link class="nav-link" :to="{name:'login'}"><small>Login</small></router-link>
                </li>
                <li class="nav-item" v-if="!isAuthenticated">
                    <router-link class="nav-link" :to="{name:'register'}"><small>Register</small></router-link>
                </li>
            </ul>
        </nav>
    </div>
</template>

<script>
import { mapState, mapActions } from 'vuex';
import $ from 'jquery';
import Notifications from '../Notifications/notifications';

export default {
  name: 'Headed',
  components: { Notifications },
  computed: {
    ...mapState('layout', {
      sidebarClose: state => state.sidebarClose,
      sidebarStatic: state => state.sidebarStatic,
    }),
  },
  computed: {
    isAuthenticated() {
      return this.$store.getters['security/isAuthenticated'];
    }
  },
  methods: {
    ...mapActions('layout', ['toggleSidebar', 'toggleChat', 'switchSidebar', 'changeSidebarActive']),
    switchSidebarMethod() {
      if (!this.sidebarClose) {
        this.switchSidebar(true);
        this.changeSidebarActive(null);
      } else {
        this.switchSidebar(false);
        const paths = this.$route.fullPath.split('/');
        paths.pop();
        this.changeSidebarActive(paths.join('/'));
      }
    },
    toggleSidebarMethod() {
      if (this.sidebarStatic) {
        this.toggleSidebar();
        this.changeSidebarActive(null);
      } else {
        this.toggleSidebar();
        const paths = this.$route.fullPath.split('/');
        paths.pop();
        this.changeSidebarActive(paths.join('/'));
      }
    },
    logout() {
      window.localStorage.setItem('authenticated', false);
      this.$router.push('/login');
    },
  },
  created() {
    if (window.innerWidth > 576) {
      setTimeout(() => {
        const $chatNotification = $('#chat-notification');
        $chatNotification.removeClass('hide').addClass('animated fadeIn')
          .one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', () => {
            $chatNotification.removeClass('animated fadeIn');
            setTimeout(() => {
              $chatNotification.addClass('animated fadeOut')
                .one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd'
                + ' oanimationend animationend', () => {
                  $chatNotification.addClass('hide');
                });
            }, 6000);
          });
        $chatNotification.siblings('#toggle-chat')
          .append('<i class="chat-notification-sing animated bounceIn"></i>');
      }, 4000);
    }
  },
};
</script>

<style  lang="scss" scoped >
.ddown {
  display:inline;
}
</style>
