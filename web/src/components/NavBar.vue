<script setup>
defineOptions({ name: 'NavBar' })
import { ref } from 'vue'
import logo from '../assets/SkillSync_Logo.png'

const isMenuOpen = ref(false)
const toggleMenu = () => (isMenuOpen.value = !isMenuOpen.value)
</script>

<template>
  <header class="navbar">
    <nav class="navbar__container container-fluid">
      <div class="row align-items-center">

        <!-- Logo -->
        <div class="col-3 navbar__logo">
          <img :src="logo" alt="SkillSync Logo" class="navbar__logo-img" />
        </div>

        <!-- Actions -->
        <div class="col-5 offset-4 navbar__actions">
          <button class="navbar__btn">
            <font-awesome-icon icon="magnifying-glass" />
          </button>
          <button class="navbar__btn">
            <font-awesome-icon icon="bell" />
          </button>

          <button class="navbar__btn navbar__hamburger"
                  type="button"
                  :aria-expanded="isMenuOpen"
                  @click="toggleMenu">
            <font-awesome-icon icon="bars" />
          </button>
        </div>
      </div>

      <!-- Mobile dropdown -->
      <div v-if="isMenuOpen" class="navbar__dropdown">
        <a class="navbar__dropdown-item" href="#">Menu Item 1</a>
        <a class="navbar__dropdown-item" href="#">Menu Item 2</a>
        <a class="navbar__dropdown-item" href="#">Menu Item 3</a>
      </div>
    </nav>
  </header>
</template>
