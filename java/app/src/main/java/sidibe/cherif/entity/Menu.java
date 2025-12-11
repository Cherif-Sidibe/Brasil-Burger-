package sidibe.cherif.entity;

import java.time.LocalDate;
import lombok.AllArgsConstructor;
import lombok.Getter;
import lombok.NoArgsConstructor;
import lombok.Setter;
import lombok.ToString;

@Getter
@Setter
@ToString
@NoArgsConstructor
@AllArgsConstructor
public class Menu {
    private int id;
    private String nom;
    private double prix;
    private String description;
    private String image;
    private int idBurger;
    private int idBoisson;
    private int idFrite;
    private boolean isArchive;
    private LocalDate createAt;
    private LocalDate updateAt;

}
