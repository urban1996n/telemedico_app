# __Aplikacja kliencka na potrzeby rekrutacji dla Telemedi.co__

## Utworzenie (pierwszego) nowego konta użytkownika tworzy konto z uprawnieniami admina
## Każde następne utworzone konto będzie miało uprawnienia użytkownika(chyba że zostanie stworzone przez administratora po zalogowaniu)
### Różnią się one tym, że użytkownik może zmieniać i usuwać tylko swoje konto, admin może zarządzać wszystkimi zasobami
### Aplikacja zabezpieczona jest przez ApiKeyProvider, po stronie API, po podaniu prawidłowego loginu i hasła w sesji zapisują się informacje niezbędne do dalszego użytkowania aplikacji(klucz api, hasło, uprawnienia, nazwa użytkownika)
### Podczas rejestracji konta użytkownika sprawdzana jest nazwa, jeśli występuje ona w bazie danych otrzymujemy powiadomienie.
### Dzieje się tak również podczas zmiany loginu, czy to przez administratora, czy użytkownika

