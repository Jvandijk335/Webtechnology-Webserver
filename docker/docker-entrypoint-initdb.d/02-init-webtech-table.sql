\connect webtech
create table if not exists highscores(
  id integer primary key generated always as identity,
  username text not null,
  score integer not null,
  created_at timestamp default current_timestamp
);
grant insert on table highscores to webtechuser;
grant select on table highscores to webtechuser;
grant update on table highscores to webtechuser;
grant delete on table highscores to webtechuser;
