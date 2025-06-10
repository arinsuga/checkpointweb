var now = new Date();
var utcMillis = now.getTime(); // UTC-based timestamp
var offsetMinutes = -now.getTimezoneOffset();
var offsetHours = offsetMinutes / 60;

console.log(`utcMillis: ${utcMillis}`);
console.log(`offsetMinutes: ${offsetMinutes}`);
console.log(`offsetHours: ${offsetHours}`);
console.log("========================================")

var now = new Date();
var utc = new Date(
    now.getUTCFullYear(),
    now.getUTCMonth(),
    now.getUTCDate(),
    now.getUTCHours(),
    now.getUTCMinutes(),
    now.getUTCSeconds(),
    now.getUTCMilliseconds()
);
console.log(`now: ${now}`);
console.log(`utc: ${utc}`);
console.log("========================================")
console.log(`nowLocal_millis_value: ${now.getMilliseconds()}`);
console.log(`now_millis_value: ${now.getTime()}`);
console.log(`utcLocal_millis_value: ${utc.getMilliseconds()}`);
console.log(`utc_millis_value: ${utc.getTime()}`);
console.log(`now_offset_value: ${-now.getTimezoneOffset()/60}`);
console.log(`utc_offset_value: ${-utc.getTimezoneOffset()/60}`);

console.log(`now_hours_value: ${now.getHours()}`);
console.log(`utc_hours_value: ${now.getUTCHours()}`);
