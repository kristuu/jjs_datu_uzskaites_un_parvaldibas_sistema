<script>
export default {
  data() {
    return {
      bigNum: 0,
      smallNum: 0,
      countOfCells: 0,
      result: [],
      diapozoneResult: [],
      sumNum1: 0,
      sumNum2: 0,
      sumResult: 0
    }
  },
  methods: {
    digitsAfterPoint(num) {
      var str = num.toString();
      if (str.indexOf('.') != -1) {
        return Number('0.' + str.split('.')[1]);
      } else {
        return 0;
      }
    },
    getResult() {
      this.result = [];
      this.diapozoneResult = [];
      let tempResult = (this.bigNum - this.smallNum) / this.countOfCells;
      let hasDigitsAfterDecimal = this.digitsAfterPoint(tempResult);
      if (hasDigitsAfterDecimal != 0) {
        let limit = Math.round(hasDigitsAfterDecimal * this.countOfCells);
        let index = 1;
        for (let i = 0; i < this.countOfCells; i++) {
          console.log(`${index}, ${limit}, ${hasDigitsAfterDecimal}`)
          if (index <= limit) {
            this.result.push(Math.ceil(tempResult))
          } else {
            this.result.push(Math.floor(tempResult))
          }
          index++;
        }
      } else {
        for (let i = 0; i < this.countOfCells; i++) {
          this.result.push(tempResult);
        }
      }
      let index = 0;
      let tempNum = this.smallNum;
      for (let item in this.result) {
        let tempEndNum = tempNum + this.result[index]
        this.diapozoneResult[index] = `${tempNum}-${tempEndNum}`;
        console.log(tempNum, ` ${item} `, Number(this.result[index]))
        tempNum = tempEndNum;
        index++;
      }
    },
    getSum() {
      this.sumResult = this.sumNum1 + this.sumNum2;
    }
  }
}
</script>

<template>
  <div class="row d-flex justify-content-center">
    <div class="col-2 mb-3">
      <label for="bigNum" class="form-label">Augstākais skaitlis</label>
      <input v-model="bigNum" v-on:input="getResult" type="number" class="form-control" id="bigNum">
    </div>
    <div class="col-2 mb-3">
      <label for="smallNum" class="form-label">Zemākais skaitlis</label>
      <input v-model="smallNum" v-on:input="getResult" type="number" class="form-control" id="smallNum">
    </div>
    <div class="col-2 mb-3">
      <label for="countOfCells" class="form-label">Šūnu skaits</label>
      <input v-model="countOfCells" v-on:input="getResult" type="number" class="form-control" id="countOfCells">
    </div>
  </div>
  <h1 class="mb-5">{{ this.result }}</h1>
  <h1 class="mb-5">{{ this.diapozoneResult }}</h1>
  <div class="row d-flex justify-content-center">
    <div class="col-2 mb-3">
      <label for="sumNum1" class="form-label">I saskaitāmais</label>
      <input v-model="sumNum1" v-on:input="getSum" type="number" class="form-control" id="sumNum1">
    </div>
    <div class="col-2 mb-3">
      <label for="sumNum2" class="form-label">II saskaitāmais</label>
      <input v-model="sumNum2" v-on:input="getSum" type="number" class="form-control" id="sumNum2">
    </div>
    <h1>{{ this.sumResult }}</h1>
  </div>
</template>

<style scoped>

</style>