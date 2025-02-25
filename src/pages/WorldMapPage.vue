<template>
  <q-page>
    <WorldMapComponent
      @onCountry = "selectedCountry"
    />

  </q-page>
</template>

<script setup>
import WorldMapComponent from "components/Map/WorldMapComponent.vue";

const selectedCountry = (countryEN,countryRU) =>{
  console.log('selected',countryEN, countryRU)
}
</script>
