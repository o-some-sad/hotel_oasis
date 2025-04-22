<template>
  <Dialog v-model:open="open">
    <DialogTrigger as-child>
      <Button class="w-fit">View</Button>
    </DialogTrigger>
    <DialogContent>
      <DialogHeader>
        <DialogTitle>Modal Title</DialogTitle>
        <DialogDescription>
          This is a modal dialog using shadcn-vue UI.
        </DialogDescription>
      </DialogHeader>
      <div>
        <!-- Modal body content here -->
        <p>Hello from the modal!</p>
      </div>
      <DialogFooter>
        <button @click="open = false">Close</button>
      </DialogFooter>
    </DialogContent>
  </Dialog>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import {
  Dialog,
  DialogContent,
  DialogDescription,
  DialogFooter,
  DialogHeader,
  DialogTitle,
  DialogTrigger,
} from '@/components/ui/dialog'
import Button from '@/components/ui/button/Button.vue';

function loadData(){
    
}

interface Props{
    id: string | number;
}

const props = defineProps<Props>()

console.log(props);

const open = ref(false)
</script>