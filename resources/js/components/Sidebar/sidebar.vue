<template>
  <nav
    :class="{sidebar: true, sidebarStatic, sidebarOpened}"
    @mouseenter="sidebarMouseEnter"
    @mouseleave="sidebarMouseLeave"
  >

    <ul class="nav">
       <form class="logo">
          <div class="form-group"> 
              <b-input size="sm" id="search-input" placeholder="Search" />
          </div>
      </form>
      <NavLink
        header="Home"
        link="/app/home"
        iconName="flaticon-home"
        index="app/home"
        isHeader
      />
      <NavLink
        header="Domain"
        link="/app/domain/home"
        iconName="flaticon-home"
        index="app/domain/"
        isHeader
      />
      <NavLink
        header="Cart"
        link="/app/cart/index"
        iconName="flaticon-list"
        index="cart"
        isHeader
      />
      <NavLink
        header="Your Order"
        link="/app/order/index"
        iconName="flaticon-list"
        index="order"
        isHeader
      />
      <NavLink
        :activeItem="activeItem"
        header="Accounts"
        link="/app/user/profile"
        iconName="flaticon-network"
        index="/app/user/profile"
        :childrenLinks="[
          { header: 'Charts', link: '/app/components/charts' },
          { header: 'Icons', link: '/app/components/icons' },
          { header: 'Maps', link: '/app/components/maps' },
        ]"
      />
    </ul>
  </nav>
</template>

<script>
import { mapState, mapActions } from 'vuex';
import isScreen from '../../core/screenHelper';
import NavLink from './NavLink/navLink';

export default {
  name: 'Sidebar',
  components: { NavLink },
  data() {
    return {
      alerts: [
        {
          id: 0,
          title: 'Sales Report',
          value: 15,
          footer: 'Calculating x-axis bias... 65%',
          color: 'info',
        },
        {
          id: 1,
          title: 'Personal Responsibility',
          value: 20,
          footer: 'Provide required notes',
          color: 'danger',
        },
      ],
    };
  },
  methods: {
    ...mapActions('layout', ['changeSidebarActive', 'switchSidebar']),
    setActiveByRoute() {
      const paths = this.$route.fullPath.split('/');
      paths.pop();
      this.changeSidebarActive(paths.join('/'));
    },
    sidebarMouseEnter() {
      if (!this.sidebarStatic && (isScreen('lg') || isScreen('xl'))) {
        this.switchSidebar(false);
        this.setActiveByRoute();
      }
    },
    sidebarMouseLeave() {
      if (!this.sidebarStatic && (isScreen('lg') || isScreen('xl'))) {
        this.switchSidebar(true);
        this.changeSidebarActive(null);
      }
    },
  },
  created() {
    this.setActiveByRoute();
  },
  computed: {
    ...mapState('layout', {
      sidebarStatic: state => state.sidebarStatic,
      sidebarOpened: state => !state.sidebarClose,
      activeItem: state => state.sidebarActiveElement,
    }),
  },
};
</script>

<!-- Sidebar styles should be scoped -->
<style src="./sidebar.scss" lang="scss" scoped></style>
