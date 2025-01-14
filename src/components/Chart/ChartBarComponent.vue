<template>
  <q-item>
    <VueApexCharts
      type="bar"
      :options="options"
      :series="series"
      class="full"
    >
    </VueApexCharts>
  </q-item>
</template>

<script setup>
import {ref, toRefs} from 'vue'
import VueApexCharts from 'vue3-apexcharts'

const props = defineProps({
  optionsX: Array,
  seriesY: Array
});

const options =  ref({
  chart: {
    id: 'vuechart',
  },
  xaxis: {
    categories: []
  },
})
const series = ref([{
  name: '111',
  data: []
}])

const { optionsX, seriesY } = toRefs(props)
options.value.xaxis.categories = optionsX
series.value[0].data = seriesY


</script>

<style lang="sass" scoped>
.full
  width: 90%
</style>
