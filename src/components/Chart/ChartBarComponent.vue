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
const emit = defineEmits(['selected'])

const options =  ref({
  chart: {
    id: 'vuechart1',
    height: 'auto',
    events: {
      click  (event, chartContext, opts){
        if (opts.seriesIndex === -1 || opts.dataPointIndex === -1)  emit('selected','')
        emit('selected', options.value.xaxis.categories[opts.dataPointIndex])
      }
    }
  },
  xaxis: {
    categories: [],
    crosshairs: {
      fill: {
        type: 'gradient',
        gradient: {
          colorFrom: '#D8E3F0',
          colorTo: '#BED1E6',
          stops: [0, 100],
          opacityFrom: 0.4,
          opacityTo: 0.5,
        }
      }
    },
    tooltip: {
      enabled: true,
    },
  },

  plotOptions: {
    bar: {
      borderRadius: 10,
    }
  },
})
const series = ref([{
  name: '',
  data: []
}])

const { optionsX, seriesY } = toRefs(props)
options.value.xaxis.categories = optionsX
series.value[0].data = seriesY


</script>

<style lang="sass" scoped>
.full
  width: 100%
</style>
