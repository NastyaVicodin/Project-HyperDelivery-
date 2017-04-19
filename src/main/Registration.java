package main;

/**
 * Created by NastyaVicodin on 3/18/2017.
 */
public class Registration {
    public int id;
    public User user;

    public Registration();
    public boolean userRegister();
    public boolean registrationConfirm();
    public boolean validateUserName();
    public int getId() {
        return id;
    }

    public void setId(int id) {
        this.id = id;
    }

    public User getUser() {
        return user;
    }

    public void setUser(String username,
             String password,
            String city,
            String auth_key,
            String firstname,
            String lastname,
            String birthday,
            String description,
            String email) {
        this.user = user;
    }
}
