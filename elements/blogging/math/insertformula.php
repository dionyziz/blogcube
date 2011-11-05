<?php
    if ( !Element( "element_logged_in" ) ) {
        return false;
    }

    bfc_start();
    ?>mathexp(4);
    g('mathtobpar').style.height = "60px";
    <?php
    bfc_end();

?><div class="mathcategories"    >
Operators: <a id="math_ax_4" href="#" onmouseover="mathexp(4)">Arrows</a><a id="math_ax_0" href="#" onmouseover="mathexp(0)">Relations</a><a id="math_ax_1" href="#" onmouseover="mathexp(1)">Arithmetic</a><a id="math_ax_2" href="#" onmouseover="mathexp(2)">Set Theory</a><a id="math_ax_3" href="#" onmouseover="mathexp(3)">Letters</a><a id="math_ax_5" href="#" onmouseover="mathexp(5)">Calculus</a><a id="math_ax_7" href="#" onmouseover="mathexp(7)">Logic</a><a id="math_ax_6" href="#" onmouseover="mathexp(6)">Others</a>
</div>
<div id="operexp">
</div>
<div style="display:none">
    <div id="math_x_4"> <!-- Mathematical -->
    <a onclick="texinsert('\\rightarrow');return false;" title="Right Arrow" href="">&rarr;</a>
    <a onclick="texinsert('\\leftarrow');return false;" title="Left Arrow" href="">&larr;</a>
    <a onclick="texinsert('\\leftrightarrow');return false;" title="Left and Right Arrow" href="">&harr;</a>
    <a onclick="texinsert('\\Rightarrow');return false;" title="Double Right Arrow" href="">&rArr;</a>
    <a onclick="texinsert('\\Leftarrow');return false;" title="Double Left Arrow" href="">&lArr;</a>
    <a onclick="texinsert('\\Leftrightarrow');return false;" title="Double Left and Right Arrow" href="">&hArr;</a>
    </div>
    <div id="math_x_0"> <!-- Relations -->
    <a onclick="texinsert('\\not=');return false;" title="Not equal" href="">&ne;</a> <!-- Not equal -->
    <a onclick="texinsert('\\le');return false;" href="">&le;</a> <!-- Less than or equal -->
    <a onclick="texinsert('\\ge');return false;" href="">&ge;</a> <!-- Greater than or equal -->
    <a onclick="texinsert('\\equiv');return false;" href="">&equiv;</a> <!-- Identical -->
    <a onclick="texinsert('\\aprox');return false;" title="Isomorphism" href="">&asymp;</a>
    <a onclick="texinsert('\\cong');return false;" title="Approximately Equal To" href="">&cong;</a>
    <a onclick="texinsert('\\propto');return false;" title="Proposional to" href="">&prop;</a>
    </div>
    <div id="math_x_1"> <!-- Arithmetic -->
    <a onclick="texinsert('\\sqrt{ expression }');return false;" title="Square Root" href="">&radic;</a> <!-- square root -->
    <a onclick="texinsert('\\sqrt[degree]{ expression }');return false;" title="Higher Root" href="">&#8731;</a>
    <a onclick="texinsert('{ numerator \\over denominator }');return false;" title="Fraction" href="">&#189;</a> <!-- fraction -->
    <a onclick="texinsert('{base}^{power}');return false;" title="Power" href="">x<sup>2</sup></a> <!-- power -->
    <a onclick="texinsert('\\pm');return false;" title="Plus Minus" href="">&plusmn;</a>
    <a onclick="texinsert('\\infty');return false;" title="Infinity" href="">&infin;</a>
    <a onclick="texinsert('\\sum_{ variable = beginvalue }^{ endvalue } { expression }');return false;" title="Sum" href="">&sum;</a>
    <a onclick="texinsert('\\prod_{ variable = beginvalue }^{ endvalue } { expression }');return false;" title="Product" href="">&prod;</a>
    <a onclick="texinsert('\\left[ {{a \\atop c} {b \\atop d}}\\right]');return false;" title="Determinant" href="">||</a>
    </div>
    <div id="math_x_2"> <!-- Sets -->
    <a onclick="texinsert('\\in ');return false;" title="In Set" href="">&isin;</a>
    <a onclick="texinsert('\\not\\in ');return false;" title="Not in Set" href="">&notin;</a>
    <a onclick="texinsert('\\ni ');return false;" title="Contains" href="">&ni;</a>
    <a onclick="texinsert('\\subset ');return false;" title="Subset" href="">&sub;</a> <!-- Subset -->
    <a onclick="texinsert('\\supset ');return false;" title="Superset" href="">&sup;</a> <!-- Superset -->
    <a onclick="texinsert('\\subseteq ');return false;" title="Proper Subset" href="">&sube;</a> <!-- Proper Subset -->
    <a onclick="texinsert('\\supseteq ');return false;" title="Proper Superset" href="">&supe;</a> <!-- Proper Superset -->
    <a onclick="texinsert('\\cap ');return false;" title="Intersection" href="">&cap;</a> <!-- Intersection -->
    <a onclick="texinsert('\\cup ');return false;" title="Union" href="">&cup;</a> <!-- Union -->
    <a onclick="texinsert('\\emptyset ');return false;" title="Empty Set" href=""><big>&empty;</big></a>
    <a onclick="texinsert('\\otimes ');return false;" title="Tensor Product" href=""><big>&otimes;</big></a>
    </div>
    <div id="math_x_3">
    <!-- <a onclick="texinsert('')" href="#">&fnof;</a> -->
    <a onclick="texinsert('\\alpha ');return false;" title="Small Alpha" href="">&alpha;</a>
    <a onclick="texinsert('\\beta ');return false;" title="Small Beta" href="">&beta;</a>
    <a onclick="texinsert('\\gamma ');return false;" title="Small Gamma" href="">&gamma;</a>
    <a onclick="texinsert('\\delta ');return false;" title="Small Delta" href="">&delta;</a>
    <a onclick="texinsert('\\epsilon ');return false;" title="Small Epsilon" href="">&epsilon;</a>
    <a onclick="texinsert('\\zeta ');return false;" title="Small Zeta" href="">&zeta;</a>
    <a onclick="texinsert('\\eta ');return false;" title="Small Eta" href="">&eta;</a>
    <a onclick="texinsert('\\theta ');return false;" title="Small Theta" href="">&theta;</a>
    <a onclick="texinsert('\\vartheta ');return false;" title="Small Theta Symbol" href="">&thetasym;</a>
    <a onclick="texinsert('\\iota ');return false;" title="Small Iota" href="">&iota;</a>
    <a onclick="texinsert('\\kappa ');return false;" title="Small Kappa" href="">&kappa;</a>
    <a onclick="texinsert('\\lambda ');return false;" title="Small Lambda" href="">&lambda;</a>
    <a onclick="texinsert('\\mu ');return false;" title="Small Mu" href="">&mu;</a>
    <a onclick="texinsert('\\nu ');return false;" title="Small Nu" href="">&nu;</a>
    <a onclick="texinsert('\\xi ');return false;" title="Small Xi" href="">&xi;</a>
    <a onclick="texinsert('\\o ');return false;" title="Small Omicron" href="">&omicron;</a>
    <a onclick="texinsert('\\pi ');return false;" title="Small Pi" href="">&pi;</a>
    <a onclick="texinsert('\\varpi ');return false;" title="Small Pi Symbol" href="">&piv;</a>
    <a onclick="texinsert('\\rho ');return false;" title="Small Rho" href="">&rho;</a>
    <a onclick="texinsert('\\sigma ');return false;" title="Small Sigma" href="">&sigma;</a>
    <a onclick="texinsert('\\tau ');return false;" title="Small Tau" href="">&tau;</a>
    <a onclick="texinsert('\\upsilon ');return false;" title="Small Upsilon" href="">&upsilon;</a>
    <a onclick="texinsert('\\phi ');return false;" title="Small Phi" href="">&phi;</a>
    <a onclick="texinsert('\\chi ');return false;" title="Small Chi" href="">&chi;</a>
    <a onclick="texinsert('\\psi ');return false;" title="Small Psi" href="">&psi;</a>
    <a onclick="texinsert('\\omega ');return false;" title="Small Omega" href="">&omega;</a><br />

    <a onclick="texinsert('\\Alpha ');return false;" title="Capital Alpha" href="">&Alpha;</a>
    <a onclick="texinsert('\\Beta ');return false;" title="Capital Beta" href="">&Beta;</a>
    <a onclick="texinsert('\\Gamma ');return false;" title="Capital Gamma" href="">&Gamma;</a>
    <a onclick="texinsert('\\Delta ');return false;" title="Capital Delta" href="">&Delta;</a>
    <a onclick="texinsert('\\Epsilon ');return false;" title="Capital Epsilon" href="">&Epsilon;</a>
    <a onclick="texinsert('\\Zeta ');return false;" title="Capital Zeta" href="">&Zeta;</a>
    <a onclick="texinsert('\\Eta ');return false;" title="Capital Eta" href="">&Eta;</a>
    <a onclick="texinsert('\\Theta ');return false;" title="Capital Theta" href="">&Theta;</a>
    <a onclick="texinsert('\\Iota ');return false;" title="Capital Iota" href="">&Iota;</a>
    <a onclick="texinsert('\\Kappa ');return false;" title="Capital Kappa" href="">&Kappa;</a>
    <a onclick="texinsert('\\Lambda ');return false;" title="Capital Lambda" href="">&Lambda;</a>
    <a onclick="texinsert('\\Mu ');return false;" title="Capital Mu" href="">&Mu;</a>
    <a onclick="texinsert('\\Nu ');return false;" title="Capital Nu" href="">&Nu;</a>
    <a onclick="texinsert('\\Xi ');return false;" title="Capital Xi" href="">&Xi;</a>
    <a onclick="texinsert('\\O ');return false;" title="Capital Omicron" href="">&Omicron;</a>
    <a onclick="texinsert('\\Pi ');return false;" title="Capital Pi" href="">&Pi;</a>
    <a onclick="texinsert('\\Rho ');return false;" title="Capital Rho" href="">&Rho;</a>
    <a onclick="texinsert('\\Sigma ');return false;" title="Capital Sigma" href="">&Sigma;</a>
    <a onclick="texinsert('\\Tau ');return false;" title="Capital Tau" href="">&Tau;</a>
    <a onclick="texinsert('\\Upsilon ');return false;" title="Capital Upsilon" href="">&Upsilon;</a>
    <a onclick="texinsert('\\Phi ');return false;" title="Capital Phi" href="">&Phi;</a>
    <a onclick="texinsert('\\Chi ');return false;" title="Capital Chi" href="">&Chi;</a>
    <a onclick="texinsert('\\Psi ');return false;" title="Capital Psi" href="">&Psi;</a>
    <a onclick="texinsert('\\Omega ');return false;" title="Capital Omega" href="">&Omega;</a><br />

    <a onclick="texinsert('\\Re ');return false;" title="Real Part" href="">&real;</a>
    <a onclick="texinsert('\\Im ');return false;" title="Imaginary Part" href="">&image;</a>
    <a onclick="texinsert('\\wp ');return false;" title="Weierstrass P" href="">&weierp;</a>
    <a onclick="texinsert('\\aleph ');return false;" title="Aleph" href="">&alefsym;</a>
    </div>
    <div id="math_x_5"> <!-- Calculus -->
    <a onclick="texinsert('\\lim_{ variable \\to number }{ function }');return false;" title="Limit" href="">lim</a>
    <a onclick="texinsert('\\int { f(x) dx }');return false;" title="Integral" href="">&int;</a>
    <a onclick="texinsert('\\int_{ baseendpoint }^{ topendpoint }{ f(x) dx }');return false;" title="Integral with EndPoints" href="">&int;<sub>a</sub></a>
    <a onclick="texinsert('\\nabla ');return false;" title="Nabla" href="">&nabla;</a>
    <a onclick="texinsert('\\circ ');return false;" title="Function Composition" href="">o</a>
    </div>
    <div id="math_x_7"> <!-- Logic -->
    <a onclick="texinsert('\\wedge');return false;" title="Logical And" href="">&and;</a>
    <a onclick="texinsert('\\vee');return false;" title="Logical Or" href="">&or;</a>
    </div>
    <div id="math_x_6"> <!-- Others -->
    <a onclick="texinsert('\\forall');return false;" title="For Every" href="">&forall;</a>
    <a onclick="texinsert('\\exists');return false;" title="Exists" href="">&exist;</a>
    </div>
</div>
<textarea id="newformula" style="width:100%;height:70px;"></textarea>
<div class="smallinks"><?php
    IconLL( Array( 
        Array( "Insert Formula" , "javascript:insertclear();" , "add" ) ,
        Array( "Back to Post" , "javascript:formulacancel();" , "back" )
    ) );
?></div>