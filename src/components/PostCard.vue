<template>
  <div class="card">
    <h2>11111</h2>
      <slot name="cardTitle"></slot>
    <input
      type="text"
      :placeholder="props.label"
      :value="props.modelValue"
      v-on:input="updateValue($event.target.value)"
    />
  </div>
</template>
<script setup>

const props = defineProps({
  modelValue: String
})

const emit = defineEmits(['update:modelValue'])

function updateValue(value) {
  emit('update:modelValue', value)
}
</script>
<style>
.card {
  max-width:250px;text-align:center;
  margin:2rem auto;padding:.6rem .8rem;
  border: .1rem solid #94A3B8;color:#64748B;
}
</style>
