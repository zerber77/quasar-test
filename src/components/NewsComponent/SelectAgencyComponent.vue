<template>
  <q-select
    filled
    use-input
    outlined
    emit-value
    map-options
    hide-selected
    fill-input
    input-debounce="0"
    v-model="selectedItemModel"
    :options="list"
    :option-value="opt => opt.id"
    :option-label="opt => opt.name" label="Выбор агентства">

  </q-select>
</template>

<script setup>
import { ref, watch, computed } from "vue";
import {getAgencies} from "components/modules/getAgencies";


const emit = defineEmits(["update:selectedPerson"]);

const list = ref([]);
let originalList = [];
let netError = ref(false)

const props = defineProps({
  inputList: {
    type: Array
  },
  selectedPerson: {
    type: Object
  }
});

originalList = props.inputList
list.value = originalList
//props.selectedPerson.fio = `${props.selectedPerson.surname} ${props.selectedPerson.name} ${props.selectedPerson.father_name}`
//list.value.push(props.selectedPerson)

const selectedItemModel = computed({
  get() {
    return props.selectedPerson.id;
  },
  set(value) {
    const item = originalList.find(s => s.id === value);
    if (item) {
      item.selected = true
      emit('update:selectedPerson', item);
    }
  }
});


// const filterFn =  async (val, update, abort) => {
//     update(() => {
//       list.value = originalList
//     })
//     netError.value = false
// }
//
// const abortFilterFn =  () =>{
//   console.log('delayed filter aborted')
// }


</script>

<style scoped>

</style>
