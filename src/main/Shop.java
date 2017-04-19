package main;

/**
 * Created by NastyaVicodin on 3/18/2017.
 */
public class Shop {
    public String name;
    public String description;
    public String adress;

    public Shop(String name, String description, String adress) {
        this.name = name;
        this.description = description;
        this.adress = adress;
    }

    public boolean AddShop();
    public boolean DeleteShop();

    public String getName() {
        return name;
    }

    public void setName(String name) {
        this.name = name;
    }

    public String getDescription() {
        return description;
    }

    public void setDescription(String description) {
        this.description = description;
    }

    public String getAdress() {
        return adress;
    }

    public void setAdress(String adress) {
        this.adress = adress;
    }
}
