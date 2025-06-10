--===================================================
--checkin
--===================================================

SELECT
id,
user_id,
attend_dt,
'Asia/Makassar' as attend_utctz,
(UNIX_TIMESTAMP(CONVERT_TZ(attend_dt, '+07:00', '+00:00')) * 1000) as attend_utcmillis,
8 as attend_utcoffset,
checkin_time,
'Asia/Makassar' as checkin_utctz,
(UNIX_TIMESTAMP(CONVERT_TZ(checkin_time, '+07:00', '+00:00')) * 1000) as checkin_utcmillis,
8 as checkin_utcoffset
FROM `attends`
WHERE checkin_time is not null
and checkin_utcmillis is null;

update `attends` set
attend_utctz = 'Asia/Makassar',
attend_utcmillis = (UNIX_TIMESTAMP(CONVERT_TZ(attend_dt, '+07:00', '+00:00')) * 1000),
attend_utcoffset = 8,
checkin_utctz = 'Asia/Makassar',
checkin_utcmillis = (UNIX_TIMESTAMP(CONVERT_TZ(checkin_time, '+07:00', '+00:00')) * 1000),
checkin_utcoffset = 8
WHERE checkin_time is not null
and checkin_utcmillis is null;

--===================================================
--checkout
--===================================================

SELECT
id,
user_id,
attend_dt,
attend_utctz,
attend_utcmillis,
attend_utcoffset,
checkin_time,
checkin_utctz,
checkin_utcmillis,
checkin_utcoffset,
checkout_time,
'Asia/Makassar' as checkout_utctz,
(UNIX_TIMESTAMP(CONVERT_TZ(checkout_time, '+07:00', '+00:00')) * 1000) as checkout_utcmillis,
8 as checkout_utcoffset
FROM `attends`
WHERE checkout_time is not null
and checkout_utcmillis is null;

update `attends` set
checkout_utctz = 'Asia/Makassar',
checkout_utcmillis = (UNIX_TIMESTAMP(CONVERT_TZ(checkout_time, '+07:00', '+00:00')) * 1000),
checkout_utcoffset = 8
WHERE checkout_time is not null
and checkout_utcmillis is null;

--===================================================

update `attends` set
attend_utctz = 'Asia/Makassar',
attend_utcmillis = (UNIX_TIMESTAMP(CONVERT_TZ(attend_dt, '+07:00', '+00:00')) * 1000),
attend_utcoffset = 8,
checkin_utctz = 'Asia/Makassar',
checkin_utcmillis = (UNIX_TIMESTAMP(CONVERT_TZ(checkin_time, '+07:00', '+00:00')) * 1000),
checkin_utcoffset = 8,
checkout_utctz = 'Asia/Makassar',
checkout_utcmillis = (UNIX_TIMESTAMP(CONVERT_TZ(checkout_time, '+07:00', '+00:00')) * 1000),
checkout_utcoffset = 8

--===================================================



