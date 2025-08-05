<script setup lang="ts">
const echo = useEcho();

const messages = ref<string[]>([])
const writeNewMessage = (e: object) => {
  messages.value.push(JSON.stringify(e));
};


function subscribeToPublicChannel() {
  const name = 'notifications'
  const event = '.order.created'

  echo.channel(name)
      .listen(event, (e: object) => writeNewMessage(e))
      .error((e: object) => {
        console.error('Public channel error', e)
      });
}

onMounted(() => {
  subscribeToPublicChannel();
});
</script>

<template>
<!--  Компонент для отображения уведомлений-->
</template>

<style scoped>

</style>