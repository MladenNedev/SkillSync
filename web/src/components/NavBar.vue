<script setup>
defineOptions({ name: 'NavBar' })
import { ref } from 'vue'
import logo from '../assets/SkillSync_Logo.png'

const isMenuOpen = ref(false)
const toggleMenu = () => (isMenuOpen.value = !isMenuOpen.value)
</script>

<template>
  <header>
    <nav class="navbar">
      <div class="container-fluid">
        <div class="row navbar-row">
          <div class="col-10 offset-1 navbar-content">
            <div class="navbar__logo">
              <img :src="logo" class="navbar__logo-img" />
            </div>

            <div class="navbar__tools">
              <!-- Search + notifications + hamburger -->
              <button class="navbar__tools-button">
                <font-awesome-icon icon="magnifying-glass" />
              </button>
              <button class="navbar__tools-button">
                <font-awesome-icon icon="bell" />
              </button>
              <button class="navbar__tools-button navbar__tools-hamburger" @click="toggleMenu">
                <font-awesome-icon icon="bars" />
              </button>
            </div>
          </div>
        </div>

        <!-- Mobile dropdown -->
        <div v-if="isMenuOpen" class="navbar__dropdown">
          <a class="navbar__dropdown-item" href="#">Menu Item 1</a>
          <a class="navbar__dropdown-item" href="#">Menu Item 2</a>
        </div>
      </div>
    </nav>
  </header>
</template>
