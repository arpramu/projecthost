CREATE OR REPLACE FUNCTION check_overlapping_reservations(
    new_check_in_date DATE, new_check_out_date DATE, room_id_param INTEGER)
RETURNS BOOLEAN AS
$$
DECLARE
    overlapping_exists BOOLEAN;
BEGIN
    SELECT EXISTS (
        SELECT 1
        FROM reservation
        WHERE reservation.room_id = room_id_param
          AND (new_check_in_date, new_check_out_date)
              OVERLAPS (reservation.check_in_date, reservation.check_out_date)
    ) INTO overlapping_exists;

    RETURN NOT overlapping_exists;
END;
$$
LANGUAGE plpgsql;




CREATE OR REPLACE FUNCTION before_insert_reservation()
RETURNS TRIGGER AS
$$
BEGIN
    IF NOT check_overlapping_reservations(NEW.check_in_date, NEW.check_out_date, NEW.room_id) THEN
        RAISE EXCEPTION 'Overlapping reservations are not allowed for the same room.';
    END IF;
    RETURN NEW;
END;
$$
LANGUAGE plpgsql;




CREATE TRIGGER before_insert_reservation_trigger
BEFORE INSERT ON reservation
FOR EACH ROW
EXECUTE FUNCTION before_insert_reservation();





CREATE OR REPLACE FUNCTION check_total_payment()
RETURNS TRIGGER AS
$$
BEGIN
    IF NEW.total_payment > 30000 THEN
        -- Log the information into a new table
        INSERT INTO exceeded_payments_log (reservation_id, total_payment)
        VALUES (NEW.reservation_id, NEW.total_payment);

        -- You can add additional actions or logic as needed
    END IF;
    RETURN NEW;
END;
$$
LANGUAGE plpgsql;

CREATE TRIGGER total_payment_trigger
BEFORE INSERT OR UPDATE ON reservation
FOR EACH ROW
EXECUTE FUNCTION check_total_payment();
