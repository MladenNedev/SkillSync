<script setup>

    const summary = await fetchSummary();
    const chart   = await fetchChart();
    const recent  = await fetchRecentCourses();



</script>

<template>

  <h1 v-if="loading" style="color: red;">Loading...</h1>
  <h1 v-else-if="error">{{ error.value  }}</h1>
  <main v-else>   
    <div class="welcome-container">
        <!-- Welcome User --> <h1> Hello, Mladen </h1>
        <!-- Description -->  <h2> Are you ready to continue with your next lesson </h2>
        <!-- Weekly overview button -->
    </div>
  </main>
</template> 