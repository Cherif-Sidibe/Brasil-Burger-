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
public class Burger {
    
    private int id;
    private String nom;
    private double prix;
    private String description;
    private String image;
    private boolean isArchive;
    private LocalDate createAt;
    private LocalDate updateAt;

    
}
