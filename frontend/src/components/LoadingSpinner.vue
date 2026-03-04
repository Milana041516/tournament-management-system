<template>
  <div class="flex justify-center items-center py-12">
    <svg 
      xmlns="http://www.w3.org/2000/svg" 
      viewBox="0 0 200 200"
      :class="sizeClass"
    >
      <circle 
        fill="#3B82F6" 
        stroke="#3B82F6" 
        stroke-width="15" 
        r="15" 
        cx="40" 
        cy="65"
      >
        <animate 
          attributeName="cy" 
          calcMode="spline" 
          dur="2" 
          values="65;135;65;" 
          keySplines=".5 0 .5 1;.5 0 .5 1" 
          repeatCount="indefinite" 
          begin="-.4"
        />
      </circle>
      <circle 
        fill="#3B82F6" 
        stroke="#3B82F6" 
        stroke-width="15" 
        r="15" 
        cx="100" 
        cy="65"
      >
        <animate 
          attributeName="cy" 
          calcMode="spline" 
          dur="2" 
          values="65;135;65;" 
          keySplines=".5 0 .5 1;.5 0 .5 1" 
          repeatCount="indefinite" 
          begin="-.2"
        />
      </circle>
      <circle 
        fill="#3B82F6" 
        stroke="#3B82F6" 
        stroke-width="15" 
        r="15" 
        cx="160" 
        cy="65"
      >
        <animate 
          attributeName="cy" 
          calcMode="spline" 
          dur="2" 
          values="65;135;65;" 
          keySplines=".5 0 .5 1;.5 0 .5 1" 
          repeatCount="indefinite" 
          begin="0"
        />
      </circle>
    </svg>
  </div>
</template>

<script>
export default {
  name: 'LoadingSpinner',
  
  props: {
    size: {
      type: String,
      default: 'medium',
      validator: (value) => ['small', 'medium', 'large'].includes(value)
    }
  },
  
  computed: {
    sizeClass() {
      const sizes = {
        small: 'w-16 h-16',
        medium: 'w-24 h-24',
        large: 'w-32 h-32'
      }
      return sizes[this.size]
    }
  }
}
</script>