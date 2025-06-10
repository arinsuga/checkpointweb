var now = new Date();
var nowOffsetMinutes = -now.getTimezoneOffset();
var nowOffsetHours = nowOffsetMinutes / 60;
var nowMillis = now.getTime(); // UTC-based timestamp

console.log(`now: ${now}`);
console.log(`nowOffsetHours: ${nowOffsetHours}`);
console.log(`nowMillis: ${nowMillis}`);
console.log("========================================")



var utc0OffsetMinutes = -now.getTimezoneOffset();
var utc0OffsetHours = utc0OffsetMinutes / 60;
var utc0 = new Date(utc0Millis);
var utc0Millis = now.setHours(now.getHours() - nowOffsetHours);

console.log(`utc0: ${utc0}`);
console.log(`utc0OffsetHours: ${utc0OffsetHours}`);
console.log(`utc0Millis: ${utc0Millis}`);
console.log("========================================")
