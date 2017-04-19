package main;

/**
 * Created by NastyaVicodin on 3/18/2017.
 */
public class Login {
    public String username;
    public String password;

    private User _user;

    public Login(String username, String password) {
        this.username = username;
        this.password = password;
    }

    public boolean validatePassword(String password);
    public boolean login();
    private User getUser();

    public String getUsername() {
        return username;
    }

    public void setUsername(String username) {
        this.username = username;
    }

    public String getPassword() {
        return password;
    }

    public void setPassword(String password) {
        this.password = password;
    }
}
