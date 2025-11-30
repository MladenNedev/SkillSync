<script setup>
defineOptions({
  name: "NavBar"
});
import logo from "../assets/SkillSync_Logo.png";
</script>

<template>
  <header>
    <nav class="container-fluid">
      <div class="row">
        <div class="col-3 nav-logo-container">
          <img :src="logo" alt="SkillSync_Logo" />
        </div>
        <div class="col-5 offset-4 nav-tools-container">
          <button class="search-button">
            <font-awesome-icon icon="magnifying-glass" class="search-icon" />
          </button>
          <button class="notifications-button">
            <font-awesome-icon icon="bell" class="notifications-icon" />
          </button>
          <button
            class="navbar-toggler hamburger-button"
            type="button"
            :aria-expanded="isMenuOpen"
            aria-label="Toggle navigation"
            @click="toggleMenu"
          >
            <font-awesome-icon icon="bars" class="hamburger-icon" />
          </button>
        </div>
      </div>
      <div
        v-if="isMenuOpen"
        id="navbarToggleContent"
        class="navbar-collapse show"
      >
        <div class="navbar-nav">
          <!-- Add your menu items here -->
          <a class="nav-link" href="#">Menu Item 1</a>
          <a class="nav-link" href="#">Menu Item 2</a>
          <a class="nav-link" href="#">Menu Item 3</a>
        </div>
      </div>
    </nav>
  </header>
</template>
