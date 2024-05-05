<script lang="ts" setup>
import {RouterLink, RouterView} from 'vue-router'
import IconLogo from "@/components/icons/IconLogo.vue";
</script>

<template>
  <div class="d-flex flex-column min-vh-100 bg-dark text-light" data-bs-theme="dark">
    <header class="container pb-3">
      <BNavbar>
        <RouterLink class="navbar-item col-2 d-flex text-decoration-none" to="/">
          <IconLogo/>
          <span class="p-2 badge fs-3 fw-bold">FLEXHUB</span>
        </RouterLink>
        <RouterLink class="navbar-item col-2 btn btn-outline-light" to="/create">New recipe</RouterLink>
      </BNavbar>
    </header>

    <BContainer class="flex-grow-1">
      <RouterView/>
    </BContainer>

    <footer>
      <BContainer class="p-2 text-center">
        <BRow>
          <BCol cols="12">
            <span>
              <a class="fs-6 text-light fw-bold" href="https://github.com/sonnymilton/flexhub" target="_blank">FLEXHUB</a>
              &mdash; Private Symfony Flex Recipes Server. {{ new Date().getFullYear() }}
            </span>
          </BCol>
        </BRow>
      </BContainer>
    </footer>
  </div>

  <BToastOrchestrator/>
</template>

<style scoped>

</style>
