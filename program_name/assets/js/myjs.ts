// class Student {
//     fullName: string;
//     constructor(public firstName: string, public middleInitial: string, public lastName: string) {
//         this.fullName = firstName + " " + middleInitial + " " + lastName;
//     }
// }

// interface Person {
//     firstName: string;
//     lastName: string;
// }

// function greeter(person : Person) {
//     return "Hello, " + person.firstName + " " + person.lastName;
// }

// let user = new Student("Jane", "M.", "User");

// document.body.innerHTML = greeter(user);

function generateNum(){
	 return new Promise(function (resolve, reject) {
    var randomNumber = Math.floor((Math.random() * 10) + 1)
    if (randomNumber <= 5) {
      setTimeout(() => resolve(randomNumber), 1000);
    } else {
      setTimeout(() => resolve(randomNumber), 1000);
    }
  })
}

//BASIC CHAINING REPLACING RESOLVE OF RESULT
// generateNum()
// .then(function(result){
// 	console.log('succses Generate:' + result);	
// 	return result;
// })
// .then(function(result){
// 	// addNum(result);
// 	console.log('Plus 2 From = ' + result + ' + 2');
// 	return addNum(result);
// })
// .then(function(result){
// 	console.log('Minus 2 From = ' + result + ' - 2');
// 	return minusNum(result);
// })
// .then((res)=>{
// 	console.log('Final:' + res);
// })
// .catch(function(error){
//     console.log('error:' + error);
// });

// function addNum(result){
//    	return result+2;
// }

// function minusNum(result){
// 	return result-2;
//}

////////////////////////////////////////CHAINING ASYNC RESOLVE//////////////////////////////////////////
generateNum()
.then(function(result){
	console.log('succses Generate:' + result);	
	return result;
})
.then((result)=>{	
	console.log('Plus 2 From = ' + result + ' + 2');
	console.log('Waiting Process II . . . . . .');
	return addNum(result);
})
.then(function(result){
	console.log('Minus 2 From = ' + result + ' - 2');
	console.log('Waiting Process III . . . . . .');
	return minusNum(result);
})
.then((res)=>{
	console.log('Final:' + res);
})
.catch(function(error){
    console.log('error:' + error);
});

function addNum(result){
   	return new Promise(function(resolve , reject){
   		setTimeout(() => resolve(result + 2), 1500);
   	})
}

function minusNum(result){
	return new Promise(function(resolve , reject){
		setTimeout(() => resolve(result - 2), 1500);
	})
}

