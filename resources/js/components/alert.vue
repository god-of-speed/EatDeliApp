<template>
     <div>
        <div v-if="Array.isArray(message)" :class="customClass" role="alert">
            <div class="row">
                <ul>
                    <li v-for="msg in message" v-bind:key="msg">{{ msg }}</li>
                </ul>
            </div>  
        </div>
        <div v-if="!Array.isArray(message)" :class="customClass">
            {{ message }}
        </div>
     </div>
</template>
<script>
     export default {
         props: ['message','type'],
         computed: {
             customClass() {
                 return `alert alert-${this.type} alert-dismissible fade show`;
             }
         }
     }
</script>
<style></style>