class Spinner extends React.Component {
  render() {
    return (
      <img
        src='./images/spinner.gif'
        style={{
          margin: 'auto',
          display: 'block'
        }}
        alt='Checking any sequence match...'
      />
    );
  }
}

class BlatForm extends React.Component {
  constructor(props) {
    super(props);
    this.state = {
      speciesScientificNames: [],
      selectedSpeciesScientificName: 'Drosophila melanogaster (dmel)',
      genomeAssemblyReleaseVersions: [],
      selectedGenomeAssemblyReleaseVersion: 'dm6',
      minimumIdentityPercentage: '95',
      sequence: '',
      loading: false,
      error: false,
      errorMessage: ''
    };
    this.changeSpeciesScientificName = this.changeSpeciesScientificName.bind(this);
    this.changeGenomeAssemblyReleaseVersion = this.changeGenomeAssemblyReleaseVersion.bind(this);
  };

  componentDidMount() {
    this.setState({
      speciesScientificNames: [
        { name: 'Aedes aegypti (aaeg)', genomeAssemblyReleaseVersions: 
          [ { name: 'aaeg5' } ]
        },
        { name: 'Anopheles gambiae (agam)', genomeAssemblyReleaseVersions:
          [ { name: 'agam4' } ]
        },
        { name: 'Drosophila melanogaster (dmel)', genomeAssemblyReleaseVersions:
          [ { name: 'dm6'}, 
            { name: 'dm3'},
            { name: 'dm2'},
            { name: 'dm1'} ] },
        { name: 'Tribolium castaneum (tcas)', genomeAssemblyReleaseVersions:
          [ { name: 'tcas5.2' } ] } 
      ],
      genomeAssemblyReleaseVersions: [
        { name: 'dm6'}, 
        { name: 'dm3'},
        { name: 'dm2'},
        { name: 'dm1'}]
    });
  }

  changeSpeciesScientificName(event) {
		this.setState({ selectedSpeciesScientificName: event.target.value });
    this.setState({ genomeAssemblyReleaseVersions: this.state.speciesScientificNames.find(speciesScientificName => speciesScientificName.name === event.target.value).genomeAssemblyReleaseVersions });
    this.setState({ selectedGenomeAssemblyReleaseVersion: this.state.speciesScientificNames.find(speciesScientificName => speciesScientificName.name === event.target.value).genomeAssemblyReleaseVersions[0]['name'] });
	}

  changeGenomeAssemblyReleaseVersion(event) {
		this.setState({ selectedGenomeAssemblyReleaseVersion: event.target.value });
	}

  handleFormSubmit = e => {
    this.setState({
      loading: true
    });    
    e.preventDefault();
    axios({
      data: this.state,
      headers: { 'content-type': 'application/json' },
      method: 'post',
      url: API_PATH + '/search'
    })
    .then(result => {
      this.setState({
        list: result.data.results[0],
        loading: false
      });      
    })
    .catch(error => this.setState({ error: error.message }));
  };

  render() {
    let output;

    if (this.state.list === null || this.state.loading) {
      output = <Spinner />;
    } else {
      if (this.state.list !== null) {
        output = <div dangerouslySetInnerHTML={{__html: this.state.list}}></div>
      } else {
        output = <p>No sequence match</p>;
      }
    }

    return ( 
      <div className="BlatForm">
        <p>BLAT server</p>
        <div>
          <form>
            <label>Species Scientific Name:&nbsp;</label>
            <select placeholder="speciesScientificNamesSelector" value={this.state.selectedSpeciesScientificName} onChange={this.changeSpeciesScientificName}>
              {this.state.speciesScientificNames.map((e, key) => {
							  return <option key="{key}">{e.name}</option>;
						  })}
					  </select><br />
            <br />
            <label>Genome Assembly Release Version:&nbsp;</label>
            <select placeholder="genomeAssemblyReleaseVersionsSelector" value={this.state.selectedGenomeAssemblyReleaseVersion} onChange={this.changeGenomeAssemblyReleaseVersion}>
						  {this.state.genomeAssemblyReleaseVersions.map((e, key) => {
							  return <option key="{key}">{e.name}</option>;
						  })}
					  </select><br />
            <br />
            <label>Identity Percentage:&nbsp;</label>
            <br />
            <input type="text" 
                   id="minimumIdentityPercentageId"
                   name="minimumIdentityPercentage"
                   value={this.state.minimumIdentityPercentage}
                   onChange={e => this.setState({ minimumIdentityPercentage: e.target.value })}/>
            <label>%</label><br />
            <br />
            <label>Sequence:&nbsp;</label><br />
            <textarea id="sequenceId"
                      name="sequence"
                      rows={10}
                      cols={100}
                      value={this.state.sequence}
                      onChange={e => this.setState({ sequence: e.target.value })}></textarea><br />
            <br />
            <input type="submit"
                   value="Submit"
                   onClick={e => this.handleFormSubmit(e)} /><br />
            <br />
            <label>Results:&nbsp;</label><br />
            <div id="outputId">{output}</div>
          </form>
        </div>
      </div>
    );
  }
}